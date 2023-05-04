<?php

namespace Sudo\AutoContent\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use ListData;
use Form;
use DB;
use Illuminate\Support\Facades\Validator;
use Sudo\AutoContent\Models\Ackeyword;
use Sudo\AutoContent\Models\AcOutline;

class AcOutlineController extends AdminController
{
    function __construct()
    {

        \Asset::addDirectly([asset('vendor/core/auto_content/css/styles.css')], 'styles');
        $this->models = new \Sudo\AutoContent\Models\Ackeyword;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Auto Content Keywords';
        $this->has_seo = false;
        parent::__construct();
    }

    public function outline(Request $request, $keyword_id) {
        $ac_keyword = Ackeyword::find($keyword_id);
        $ac_outline = AcOutline::where('keyword_id',$keyword_id)->first();
        return view('AutoContent::ac_outlines.outline',compact('keyword_id','ac_keyword','ac_outline'));
    }

    public function save(Request $request) {
        $outline = AcOutline::updateOrCreate(
            ['keyword_id' => $request->keyword_id],
            [
                'analysis' => base64_encode(json_encode($request->analysis??'')),
                'outlines' => base64_encode(json_encode($request->outlines??'')),
                'status' => 1
            ]
        );
        if($outline->id) {
            return json_encode([
                'status' => 1,
                'message' => 'Lưu thành công!'
            ]);
        }else {
            return json_encode([
                'status' => 0,
                'message' => 'Có lỗi xảy ra!'
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
