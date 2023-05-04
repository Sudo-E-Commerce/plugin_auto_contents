<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('primary_keyword')->unique();//từ khóa chính
            $table->string('sub_keyword');//danh sách 3-10 từ khóa phụ phân cách bởi dấu phẩy ","
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_keywords');
    }
}
