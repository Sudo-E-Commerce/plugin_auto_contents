<?php

namespace Sudo\AutoContent\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use DB;
use Exception;
use Log;
use OpenAI;

class GptController extends AdminController
{
	// Get content from chat gpt
    public function getContentFromChatGPT(Request $request){
        try{
            $title = $request->title ?? '';
            $heading = $request->heading ?? '';
            $type = strtolower($request->type ?? '');
            $outline = $request->outline ?? '';
            $child_headings = $request->child_headings ?? '';
            $primary_keyword = $request->primary_keyword ?? '';
            $general_ai = getOption('general_ai');
            $domain = $general_ai['domain'] ?? '';
            $content_website = $general_ai['content_website'] ?? '';
            $trademark = $general_ai['trademark'] ?? '';
            $purpose = $general_ai['purpose'] ?? '';
            $object = $request->object ?? '';
            $age = $request->age ?? '';
            $gender = $request->gender ?? '';
            $purpose_post = $request->purpose_post ?? '';
            $lenght_text = $request->lenght ?? '';
            $previous_heading = $request->title_A ?? '';
            $current_heading = $request->title_B ?? '';
            $current_detail = $request->detail_B ?? '';
            $style_post = $request->style_post ?? '';
            if(empty($title)){
                throw new Exception('Tiêu đề không thể để trống');
            }

            if(empty($type)){
                throw new Exception('Tham số type không thể để trống');
            }

            if(empty($heading)){
                throw new Exception('Heading không thể để trống');
            }
            if(empty($outline)){
                throw new Exception('Dàn ý không thể để trống');
            }
            $yourApiKey = $general_ai['account_ai'] ?? '';
            $temperature = 0.5;
            $typeHeadings = getOption('type_heading');
            $typeHeadings = collect($typeHeadings['type_heading'] ?? [])->map(function($item) {
                return (object) $item;
            });
            $typeSelect = $typeHeadings->where('key', $type)->first();
            $prompt = $typeSelect->prompt ?? '';
            $prompt = str_replace(['{title}', '{outline}', '{heading}', '{primary_keyword}', '{domain}', '{content_website}', '{trademark}', '{purpose}', '{object}', '{age}', '{gender}', '{purpose_post}', '{style_post}', '{lenght_text}', '{previous_heading}', '{current_heading}', '{current_detail}'], [$title, $outline, $heading, $primary_keyword,$domain, $content_website, $trademark, $purpose, $object, $age, $gender, $purpose_post, $style_post, $lenght_text, $previous_heading, $current_heading, $current_detail], $prompt);
            if(empty($prompt)){
                throw new Exception('Heading không thể để trống');
            }
            if(isset($yourApiKey) && $yourApiKey != '') {
                $client = OpenAI::client($yourApiKey);
                $result = $client->completions()->create([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt,
                    'max_tokens' => 2048,
                    'temperature' => $temperature
                ]);
                $answer = ($result->choices[0] ?? (object)[])->text ?? '';
                $answer = ltrim($answer, "\n");
                $answer = str_replace("\n","<br>", $answer);
                if(!$answer){
                    throw new \Exception('Kết quả trả về trống');
                }
                return response()->json(['success' => 1, 'message' => 'Thành công', 'data' => [
                    'answer' => $answer,
                    'prompt' => $prompt,
                ]]);
            }else {
                return response()->json(['success' => 2, 'message' => 'Lỗi kết nối']);
            }
            throw new Exception("Error getting data chat gpt", 1);

        }catch(\Exception $e){
            \Log::error($e);
            return response()->json(['success' => 0, 'message' => $e->getMessage()]);
        }
    }


    // Get content from chat gpt
    public function getRewriteContentFromChatGPT(Request $request){
        try{
            $title = $request->title ?? '';
            $heading = $request->heading ?? '';
            $type = strtolower($request->type ?? '');
            $outline = $request->outline ?? '';
            $text = $request->text ?? '';

            if(empty($title)){
                throw new Exception('Tiêu đề không thể để trống');
            }
            if(empty($text)){
                throw new Exception('Bạn chưa chọn text');
            }

            if(empty($type)){
                throw new Exception('Tham số type không thể để trống');
            }
            $general_ai = getOption('general_ai');
            $yourApiKey = $general_ai['account_ai'] ?? '';
            $temperature = 0.5;
            if($type == 'write') {
                $prompt = '';
                $typeWrite = getOption('type_write');
                $prompt = $typeWrite['type_write']['prompt'] ?? '';
            }else{
                $typeRewrite = getOption('type_rewrite');
                $typeRewrite = collect($typeRewrite['type_rewrite'] ?? [])->map(function($item) {
                    return (object) $item;
                });
                $typeSelect = $typeRewrite->where('key', $type)->first();
                $prompt = $typeSelect->prompt ?? '';
            }
            $prompt = str_replace(['{title}', '{outline}', '{heading}', '{text}'], [$title, $outline, $heading, $text], $prompt);
            if(empty($prompt)){
                throw new Exception('Heading không thể để trống');
            }
            if(isset($yourApiKey) && $yourApiKey != '') {
                $client = OpenAI::client($yourApiKey);
                $response = $client->completions()->create([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt,
                    'max_tokens' => 2048,
                    'temperature' => $temperature
                ]);
                $answer = [];
                foreach ($response->choices as $result) {
                    $answer[] = $result->text;
                }
                $answer = ltrim($answer, "\n");
                $answer = str_replace("\n","<br>", $answer);
                if(!count($answer)){
                    throw new \Exception('Kết quả trả về trống');
                }
                return response()->json(['success' => 1, 'message' => 'Thành công', 'data' => [
                    'answer' => $answer,
                    'prompt' => $prompt,
                ]]);
            }else {
                return response()->json(['success' => 2, 'message' => 'Lỗi kết nối']);
            }
            throw new Exception("Error getting data chat gpt", 1);

        }catch(\Exception $e){
            \Log::error($e);
            return response()->json(['success' => 0, 'message' => $e->getMessage()]);
        }
    }
    //tải extension
    public function downloadExtension()
    {
        return response()->download(public_path('vendor/core/auto_content/sudo-auto-content-extension.zip'));
    }
}