<?php 
/**
 * Lấy nhanh ngôn ngữ hiện tại dùng cho cả controller và blade
 */

function getContentByKeywordId($keyword_id){
    $title = '';
    $primary_keyword = '';
    $secondary_keyword = '';
    $detail = '';
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
    // $detail = '<h2>Mở đầu</h2>' . $detail . '<h2>Kết luận</h2>';
    $detail = $detail;

    return (object)compact('title', 'detail', 'primary_keyword', 'secondary_keyword');
}
function openAiCompletion($prompt,$type=1) { //type = 1: write | type = 2: rewrite
    $generalAi = getOption('general_ai');
    $modelAi = $generalAi['model'] ?? 1;
    $yourApiKey = $generalAi['account_ai'] ?? '';
    $temperature = 0.5;
    if (isset($yourApiKey) && $yourApiKey != '') {
        $client = OpenAI::client($yourApiKey);
        $model = ($modelAi == 1) ? 'gpt-3.5-turbo' : 'text-davinci-003';
        $messages = [['role' => 'user', 'content' => $prompt]];
        $response = ($modelAi == 1)
            ? $client->chat()->create(['model' => $model, 'messages' => $messages])
            : $client->completions()->create(['model' => $model, 'prompt' => $prompt, 'max_tokens' => 2048, 'temperature' => $temperature]);
        if($type == 2) {
            $answer = [];
            foreach ($response->choices as $result) {
                $text = ($modelAi == 1) ? ltrim($result->message->content, "\n") : ltrim($result->text, "\n");
                $text = str_replace("\n","<br>", $text);
                $answer[] = $text;
            }
        }else {
            $answer = ($modelAi == 1) ? (($response->choices[0] ?? (object)[])->message->content ?? '') : (($response->choices[0] ?? (object)[])->text ?? '');
            $answer = ltrim($answer, "\n");
            $answer = str_replace("\n","<br>", $answer);
        }
        if (empty($answer)) {
            throw new \Exception('Kết quả trả về trống');
        }
        return $answer;
    } else {
        return response()->json(['success' => 2, 'message' => 'Lỗi kết nối']);
    }
}


