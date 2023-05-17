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
            $answer = openAiCompletion($prompt);
            if(isset($answer) && $answer != '') {
                return response()->json(['success' => 1, 'message' => 'Thành công', 'data' => [
                    'answer' => $answer,
                    'prompt' => $prompt,
                ]]);
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
            $type = 2;
            $answer = openAiCompletion($prompt, $type) ?? [];
            if(isset($answer) && count($answer)) {
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

    public function getFirstAndLastContent(Request $request) {
        $keyword_id = $request->keyword_id ?? 0;
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
        if(isset($yourApiKey) && $yourApiKey != '') {
            $client = \OpenAI::client($yourApiKey);
            if(isset($prompt_foreword) && $prompt_foreword != '') {
                $prompt_foreword = str_replace(['{title}', '{outline}', '{primary_keyword}', '{domain}', '{content_website}', '{trademark}', '{purpose}'], [$title, $detail, $primary_keyword,$domain, $content_website, $trademark, $purpose], $prompt_foreword);
                $answer_foreword = openAiCompletion($prompt_foreword);
                if(!$answer_foreword){
                    throw new \Exception('Kết quả trả về trống');
                }
            }
            if(isset($prompt_end) && $prompt_end != '') {
                $prompt_end = str_replace(['{title}', '{outline}', '{primary_keyword}', '{domain}', '{content_website}', '{trademark}', '{purpose}'], [$title, $detail, $primary_keyword,$domain, $content_website, $trademark, $purpose], $prompt_end);
                $answer_end = openAiCompletion($prompt_foreword);
                if(!$answer_end){
                    throw new \Exception('Kết quả trả về trống');
                }
            }
            // dd($answer_foreword, $answer_end);
            return response()->json(['success' => 1, 'first' => $answer_foreword, 'last' => $answer_end]);
        }else {
            return response()->json(['success' => 2, 'message' => 'Lỗi kết nối']);
        }
    }
}