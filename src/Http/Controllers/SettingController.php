<?php

namespace Sudo\AutoContent\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use DB;
use Form;

class SettingController extends AdminController
{
    function __construct() {
        parent::__construct();
        $this->models = new \Sudo\Base\Models\Setting;
        $this->table_name = $this->models->getTable();
    }

    //Cấu hình nội dung loại heading
    public function general_ai(Request $requests) {
        $setting_name = 'general_ai';
        $module_name = "Cấu hình Tư duy cho AI";
        $note = "Translate::form.require_text";
        // Thêm hoặc cập nhật dữ liệu
        if (isset($requests->redirect)) {
            $this->models->postData($requests, $setting_name);
        }
        // Lấy dữ liệu ra
        $data = $this->models->getData($setting_name);
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-3');
            $form->custom('AutoContent::admin.custom.sitebar_auto', ['setting_name' => $setting_name]);
        $form->endCard();
        $form->card('col-lg-9', 'Cấu hình tư duy cho AI');
            $model_api = [
                1 => 'gpt-3.5-turbo',
                2 => 'text-davinci-003',
            ];
            $form->select('model', $data['model'] ?? 1, 0, 'Chọn model', $model_api, 0, [], true, 'col-lg-12');
            $form->text('account_ai', $data['account_ai'] ?? '', 0, 'API tài khoản chatGPT','', true);
            $form->text('domain', $data['domain'] ?? '', 0, 'Domain','', true);
            $form->text('content_website', $data['content_website'] ?? '', 0, 'Nội dung Website','', true);
            $form->text('trademark', $data['trademark'] ?? '', 0, 'Tên thương hiệu','', true);
            $form->text('purpose', $data['purpose'] ?? '', 0, 'Mục đích','', true);
            $form->text('text_short', $data['text_short'] ?? '160', 0, 'Độ dài: Ngắn (160 từ)','', true);
            $form->text('text_medium', $data['text_medium'] ?? '300', 0, 'Độ dài: Trung bình (300 từ)','', true);
            $form->text('text_long', $data['text_long'] ?? '500', 0, 'Độ dài: Dài (500 từ)','', true);
        $form->endCard();
        $form->action('editconfig');
        // Hiển thị form tại view
        return $form->render('custom', compact(
            'module_name', 'note'
        ), 'AutoContent::admin.settings.form');
    }
    //Cấu hình nội dung loại heading
    public function type_heading(Request $requests) {
        $setting_name = 'type_heading';
        $module_name = "Cấu hình loại heading content";
        $note = "Translate::form.require_text";
        // Thêm hoặc cập nhật dữ liệu
        if (isset($requests->redirect)) {
            $this->models->postData($requests, $setting_name);
        }
        // Lấy dữ liệu ra
        $data = $this->models->getData($setting_name);
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-3');
            $form->custom('AutoContent::admin.custom.sitebar_auto', ['setting_name' => $setting_name]);
        $form->endCard();
        $form->card('col-lg-9', 'Cấu hình loại heading content');
            $form->custom('AutoContent::admin.settings.type_heading',  compact('data'));
        $form->endCard();
        $form->action('editconfig');
        // Hiển thị form tại view
        return $form->render('custom', compact(
            'module_name', 'note'
        ), 'AutoContent::admin.settings.form');
    }
    //Cấu hình nội dung loại heading viết lại
    public function type_rewrite(Request $requests) {
        $setting_name = 'type_rewrite';
        $module_name = "Cấu hình loại heading viết lại";
        $note = "Translate::form.require_text";
        // Thêm hoặc cập nhật dữ liệu
        if (isset($requests->redirect)) {
            $this->models->postData($requests, $setting_name);
        }
        // Lấy dữ liệu ra
        $data = $this->models->getData($setting_name);
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-3');
            $form->custom('AutoContent::admin.custom.sitebar_auto', ['setting_name' => $setting_name]);
        $form->endCard();
        $form->card('col-lg-9', 'Cấu hình loại heading viết lại');
            $form->custom('AutoContent::admin.settings.type_rewrite',  compact('data'));
        $form->endCard();
        $form->action('editconfig');
        // Hiển thị form tại view
        return $form->render('custom', compact(
            'module_name', 'note'
        ), 'AutoContent::admin.settings.form');
    }
    ///heading viết thêm
    public function type_write(Request $requests) {
        $setting_name = 'type_write';
        $module_name = "Cấu hình loại heading viết thêm";
        $note = "Translate::form.require_text";
        // Thêm hoặc cập nhật dữ liệu
        if (isset($requests->redirect)) {
            $this->models->postData($requests, $setting_name);
        }
        // Lấy dữ liệu ra
        $data = $this->models->getData($setting_name);
        // Khởi tạo form
        $form = new Form;
        $form->card('col-lg-3');
            $form->custom('AutoContent::admin.custom.sitebar_auto', ['setting_name' => $setting_name]);
        $form->endCard();
        $form->card('col-lg-9', 'Cấu hình loại heading viết thêm');
            $form->custom('AutoContent::admin.settings.type_write',  compact('data'));
        $form->endCard();
        $form->action('editconfig');
        // Hiển thị form tại view
        return $form->render('custom', compact(
            'module_name', 'note'
        ), 'AutoContent::admin.settings.form');
    }

}
