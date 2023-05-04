<?php

namespace Sudo\AutoContent\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use ListData;
use Form;
use DB;
use ListCategory;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Import\V2GeneralImports;

class AcKeywordController extends AdminController
{
    function __construct()
    {
    	$this->models = new \Sudo\AutoContent\Models\Ackeyword;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Auto Content Keywords';
        $this->has_seo = false;
        $this->has_locale = false;
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests) {
        $listdata = new ListData($requests, $this->models, 'AutoContent::ac_keyword.table', $this->has_locale);

        // Build Form tìm kiếm
        $listdata->search('primary_keyword', 'Từ khóa chính', 'string');
        $listdata->search('sub_keyword', 'Từ khóa phụ', 'string');
        // Build các hành động
        $listdata->action('status');
        // $listdata->table_simple();
        // $listdata->no_paginate();
        // $listdata->no_trash();
        
        // Build bảng
        $listdata->add('primary_keyword', 'Từ khóa chính', 1);
        $listdata->add('sub_keyword', 'Từ khóa phụ', 1);
        $listdata->add('', 'Dàn ý', 0);
        $listdata->add('', 'Hành động', 0, 'action');
        
        return $listdata->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $form = new Form;
        $form->lang($this->table_name, true);
        $form->text('primary_keyword', '', 1, 'Từ khóa chính', 'Nhập 1 từ khóa chính', true);
        $form->text('sub_keyword', '', 1, 'Từ khóa phụ', '3-10 từ khóa phụ, phân cách bởi dấu phẩy', true);
        $form->checkbox('status', 1, 1, 'Trạng thái');
        $form->action('add');
        // Hiển thị form tại view
        return $form->render('create_and_show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $requests)
    {
         // Xử lý validate
        validateForm($requests, 'primary_keyword', 'Bạn chưa nhập từ khóa chính.');
        validateForm($requests, 'primary_keyword', 'Từ khóa chính bị trùng.', 'unique', 'unique:ac_keywords');

        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        $created_at = $created_at ?? date('Y-m-d H:i:s');
        $updated_at = $updated_at ?? date('Y-m-d H:i:s');
        
        //check trùng từ khóa phụ
        $validator = Validator::make($requests->all(), []);
        $sub_keyword_explode = array_filter(explode(',',$sub_keyword));
        if(count($sub_keyword_explode) < 3 || count($sub_keyword_explode) > 10) {
            $validator->after(function ($validator) {
                $validator->errors()->add('sub_keyword', 'Số lượng từ khóa phụ từ 3-10');
            });
        }
        foreach($sub_keyword_explode as $value) {
            $searchTerm = trim($value);
            if(DB::table($this->table_name)->where('sub_keyword', 'LIKE', "$searchTerm,%")->orWhere('sub_keyword', 'LIKE', "%,$searchTerm")->orWhere('sub_keyword', 'LIKE', "%,$searchTerm,%")->exists()) {
                $validator->after(function ($validator) use ($searchTerm) {
                    $validator->errors()->add('sub_keyword', 'Từ khóa phụ bị trùng: '.$searchTerm);
                });
            }
        }
        $validator->validate();
        
        $sub_keyword = implode(',',$sub_keyword_explode);
         // Nếu click lưu nháp
        if($redirect == 'save'){
            $status = 0;
            $redirect = 'edit';
        }
        $compact = compact('primary_keyword','sub_keyword','sub_keyword','created_at','updated_at');
        $id = $this->models->createRecord($requests, $compact, $this->has_seo, $this->has_locale);
       // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
            'type' => 'success',
            'message' => __('Translate::admin.create_success')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data_edit = $this->models->where('id', $id)->first();
        $form = new Form;
        $form->lang($this->table_name, true);
        $form->text('primary_keyword', $data_edit->primary_keyword ?? '', 1, 'Từ khóa chính', 'Nhập 1 từ khóa chính', true);
        $form->text('sub_keyword', $data_edit->sub_keyword ?? '', 1, 'Từ khóa phụ', '3-10 từ khóa phụ, phân cách bởi dấu phẩy', true);
        $form->checkbox('status', $data_edit->status, 1, 'Trạng thái');
        $form->action('edit');
        return $form->render('edit_and_show', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requests, $id)
    {
          // Xử lý validate
        validateForm($requests, 'primary_keyword', 'Bạn chưa nhập từ khóa chính.');
        validateForm($requests, 'primary_keyword', 'Từ khóa chính bị trùng.', 'unique', 'unique:ac_keywords,primary_keyword,'.$id);
      // Lấy bản ghi
        $data_edit = $this->models->where('id', $id)->first();
        // Các giá trị mặc định
        $status = 0;
        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Chuẩn hóa lại dữ liệu
        $updated_at = $updated_at ?? date('Y-m-d H:i:s');

        //check trùng từ khóa phụ
        $validator = Validator::make($requests->all(), []);
        $sub_keyword_explode = array_filter(explode(',',$sub_keyword));
        if(count($sub_keyword_explode) < 3 || count($sub_keyword_explode) > 10) {
            $validator->after(function ($validator) {
                $validator->errors()->add('sub_keyword', 'Số lượng từ khóa phụ từ 3-10');
            });
        }
        foreach($sub_keyword_explode as $value) {
            $searchTerm = trim($value);
            $is_duplicate = DB::table($this->table_name)
                            ->where('id','!=',$id)
                            ->where(function($query) use ($searchTerm){
                                $query->where('sub_keyword', 'LIKE', "$searchTerm,%");
                                $query->orWhere('sub_keyword', 'LIKE', "%,$searchTerm");
                                $query->orWhere('sub_keyword', 'LIKE', "%,$searchTerm,%");
                            })
                            ->exists();
            if($is_duplicate) {
                $validator->after(function ($validator) use ($searchTerm) {
                    $validator->errors()->add('sub_keyword', 'Từ khóa phụ bị trùng: '.$searchTerm);
                });
            }
        }
        $validator->validate();

        $sub_keyword = implode(',',$sub_keyword_explode);

       $compact = compact('sub_keyword','primary_keyword','status', 'updated_at');
        // Cập nhật tại database
        $this->models->updateRecord($requests, $id, $compact, $this->has_seo);
        // // log
        // $old = [
        //     'primary_keyword'=>$data_edit->primary_keyword,
        //     'sub_keyword'=>$data_edit->sub_keyword,
        //     'status'=>$data_edit->status,
        //     'updated_at'=>$data_edit->updated_at
        // ];
        // $this->systemLogs('Sửa '.$this->module_name,'update',$this->table_name,$id,['old'=>$old,'new'=>$compact]);
        // Điều hướng
        return redirect(route('admin.'.$this->table_name.'.'.$redirect, $id))->with([
            'type' => 'success',
            'message' => __('Translate::admin.update_success')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->hasRole($this->table_name.'_delete')) {
            $data = DB::table($this->table_name)->find($id);
            if(!$data){
                return response()->json(['status'=>0, 'message'=>'Không tìm thấy bản ghi']);
            }

            DB::table($this->table_name)->where('id',$id)->delete();
            $this->systemLogs('Xóa '.$this->module_name,'delete',$this->table_name,$id);
            return response()->json(['status'=>1,'message'=>'Xóa thành công']);
        }else {
            return response()->json(['status'=>0,'message'=>'Bạn không có quyền xóa']);
        }
    }

    public function download() {
        $file = public_path('/ac-keywords.xlsx');
        return \Response()->download($file);
    }

    public function upload() {
        $this->checkRole($this->table_name.'_create');
        return view('admin.ac_keywords.upload');
    }

    public function import(Request $request) {
        $this->checkRole($this->table_name.'_create');

        $validator = Validator::make($request->all(), [
            'ac_keywords' => ['required', 'file', 'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ],[
            'ac_keywords.required' => 'Vui lòng chọn file',
            'ac_keywords.file' => 'file file file',
            'ac_keywords.mimetypes' => 'File không đúng định dạng'
        ]);


        $file = $request->file('ac_keywords');
        $path = $file->store('temp');

        $data = Excel::toArray(new V2GeneralImports, $path);

        $sheet = isset($data[0]) ? $data[0] : [];

        if(count($sheet) == 0) {
            $validator->after(function ($validator) {
                $validator->errors()->add('ac_keywords', 'File không có dữ liệu');
            });
        }elseif(count($sheet) > 500) {
            $validator->after(function ($validator) {
                $validator->errors()->add('ac_keywords', 'Số lượng dữ liệu quá lớn, chỉ chấp nhận dữ liệu dưới 500 dòng');
            });
        }elseif(!isset($sheet[0]['primary_keyword']) || !isset($sheet[0]['sub_keyword'])) {
            $validator->after(function ($validator) {
                $validator->errors()->add('ac_keywords', 'File không đúng mẫu hoặc thiếu từ khóa trong 1 hàng nào đó!');
            });
        }
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator);
        // }
        $validator->validate();

        $succes_count = 0;
        $error_primary_keyword_duplicate = $error_sub_keyword_number = $error_sub_keyword_duplicate = [];

        $created_at = $updated_at = date("Y-m-d H:i:s");
        $status = 1;
        
        foreach ($sheet as $value) {
            $primary_keyword = trim($value['primary_keyword']);
            $sub_keyword = trim($value['sub_keyword']);
            $sub_keyword_explode = array_filter(explode(',',$sub_keyword));
            if(count($sub_keyword_explode) < 3 || count($sub_keyword_explode) > 10) {
                array_push($error_sub_keyword_number, $primary_keyword);
                continue;
            }
            if(DB::table($this->table_name)->where('primary_keyword', $primary_keyword)->exists()) {
                array_push($error_primary_keyword_duplicate, $sub_keyword);
                continue;
            }
            foreach($sub_keyword_explode as $v) {
                $s = trim($v);
                if(DB::table($this->table_name)->where('sub_keyword', 'LIKE', "$s,%")->orWhere('sub_keyword', 'LIKE', "%,$s")->orWhere('sub_keyword', 'LIKE', "%,$s,%")->exists()) {
                    array_push($error_sub_keyword_duplicate, $searchTerm);
                    continue;
                }
            }

            $sub_keyword = implode(',',$sub_keyword_explode);
            $data_insert = compact('primary_keyword','sub_keyword','status','created_at','updated_at');
            $id_insert = DB::table($this->table_name)->insertGetId($data_insert);
            if($id_insert) {
                $succes_count++;
            }
        }

        $flash_level = 'success';
        $flash_message = 'Thêm mới thành công '.$succes_count.' keywords';
        return redirect()->back()->with(compact('flash_level','flash_message','error_primary_keyword_duplicate','error_sub_keyword_number','error_sub_keyword_duplicate'));
        //return view('admin.ac_keywords.upload', compact('succes_count','error_primary_keyword_duplicate','error_sub_keyword_number','error_sub_keyword_duplicate'));
    }
}
