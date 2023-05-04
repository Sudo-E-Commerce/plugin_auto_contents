<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcOutlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_outlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('keyword_id')->default(0); // id của ac_keywords tạo outline này
            $table->bigInteger('post_id')->default(0); // id của bài viết sinh bởi outline này
            $table->longtext('analysis')->nullable(); // json array dữ liệu phân tích từ top
            $table->longtext('outlines')->nullable(); // json array dữ liệu outline lập bởi người thực hiện
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
        Schema::dropIfExists('ac_outlines');
    }
}
