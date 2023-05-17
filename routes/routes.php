<?php
$namespace = 'Sudo\AutoContent\Http\Controllers';

Route::namespace($namespace)->name('admin.')->prefix(config('app.admin_dir'))->middleware(['web', 'auth-admin', '2fa'])->group(function() {
	Route::resource('ac_keywords', 'AcKeywordController');
	Route::get('ac_keywords_download','AcKeywordController@download')->name('ac_keywords_download');
    Route::get('ac_keywords_upload','AcKeywordController@upload')->name('ac_keywords_upload');
    Route::post('ac_keywords_import','AcKeywordController@import')->name('ac_keywords_import');
    Route::get('ac_outline/{keyword_id}','AcOutlineController@outline')->name('ac_outline');
    Route::post('ac_outline_save','AcOutlineController@save')->name('ac_outline_save');
	// setting
    Route::name('settings.')->group(function(){
		//Cấu hình type heading auto content
		Route::match(['GET', 'POST'], 'type_heading', 'SettingController@type_heading')->name('type_heading');
		//Cấu hình type heading  viết lại
		Route::match(['GET', 'POST'], 'type_rewrite', 'SettingController@type_rewrite')->name('type_rewrite');
		//Cấu hình heading viết thêm
		Route::match(['GET', 'POST'], 'type_write', 'SettingController@type_write')->name('type_write');
		//Cấu hình chung
		Route::match(['GET', 'POST'], 'general_ai', 'SettingController@general_ai')->name('general_ai');
	});
	Route::prefix('ajax')->name('ajax')->group(function(){
		// generate content
		Route::post('/getContentFromChatGPT', 'GptController@getContentFromChatGPT');
		Route::post('/getRewriteContentFromChatGPT', 'GptController@getRewriteContentFromChatGPT');
		Route::post('get-first-last-content', 'GptController@getFirstAndLastContent');
	});
	Route::get('download-extension','GptController@downloadExtension')->name('download_extension');
});
