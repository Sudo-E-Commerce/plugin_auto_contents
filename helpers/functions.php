<?php 
/**
 * Lấy nhanh ngôn ngữ hiện tại dùng cho cả controller và blade
 */

function getContentByKeywordId($keyword_id){
        $general_ai = getOption('general_ai');
        $typeHeadings = getOption('type_heading');
        $domain = $general_ai['domain'] ?? '';
        $content_website = $general_ai['content_website'] ?? '';
        $trademark = $general_ai['trademark'] ?? '';
        $purpose = $general_ai['purpose'] ?? '';
        $yourApiKey = $general_ai['account_ai'] ?? '';
        $title = '';
        $primary_keyword = '';
        $secondary_keyword = '';
        $detail = '';
        $temperature = 0.5;
        $prompt_foreword = $typeHeadings['type_foreword'] ?? '';
        $prompt_end = $typeHeadings['type_end'] ?? '';
        $answer_foreword = '';
        $answer_end = '';
        $ac_outline = \DB::table('ac_outlines')->where('keyword_id', $keyword_id)->first();
        if($ac_outline){
            $outlines = json_decode(base64_decode($ac_outline->outlines));
            $ac_keyword = \DB::table('ac_keywords')->where('id', $keyword_id)->first();

            $primary_keyword = $ac_keyword->primary_keyword;
            $secondary_keyword = $ac_keyword->sub_keyword;

            if(!is_array($outlines)){
                $outlines = [];
            }

            foreach ($outlines as $key => $heading) {
                if(!$title && $heading->tag == 1){
                    $title = $heading->text;
                }else{
                    $detail .= "<h{$heading->tag}>{$heading->text}</h{$heading->tag}>";
                }

            }
        }
        $prompt_end = str_replace(['{title}', '{outline}', '{primary_keyword}', '{domain}', '{content_website}', '{trademark}', '{purpose}'], [$title, $detail, $primary_keyword,$domain, $content_website, $trademark, $purpose], $prompt_end);
        if(isset($yourApiKey) && $yourApiKey != '') {
            $client = \OpenAI::client($yourApiKey);
            if(isset($prompt_foreword) && $prompt_foreword != '') {
                $prompt_foreword = str_replace(['{title}', '{outline}', '{primary_keyword}', '{domain}', '{content_website}', '{trademark}', '{purpose}'], [$title, $detail, $primary_keyword,$domain, $content_website, $trademark, $purpose], $prompt_foreword);
                $result_foreword = $client->completions()->create([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt_foreword,
                    'max_tokens' => 2048,
                    'temperature' => $temperature
                ]);
                $answer_foreword = ($result_foreword->choices[0] ?? (object)[])->text ?? '';
                $answer_foreword = ltrim($answer_foreword, "\n");
                $answer_foreword = str_replace("\n","<br>", $answer_foreword);
                if(!$answer_foreword){
                    throw new \Exception('Kết quả trả về trống');
                }
            }
            if(isset($prompt_end) && $prompt_end != '') {
                $result_end = $client->completions()->create([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt_end,
                    'max_tokens' => 2048,
                    'temperature' => $temperature
                ]);
               
                $answer_end = ($result_end->choices[0] ?? (object)[])->text ?? '';
                $answer_end = ltrim($answer_end, "\n");
                $answer_end = str_replace("\n","<br>", $answer_end);
                if(!$answer_end){
                    throw new \Exception('Kết quả trả về trống');
                }
            }
        }else {
            return response()->json(['success' => 2, 'message' => 'Lỗi kết nối']);
        }

        $detail = $answer_foreword . $detail . $answer_end;
        return (object)compact('title', 'detail', 'primary_keyword', 'secondary_keyword');
    }

