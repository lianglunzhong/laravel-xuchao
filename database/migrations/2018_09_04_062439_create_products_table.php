<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->commemt('名称');
            $table->string('description')->commemt('描述');
            $table->decimal('price', 10, 2)->commemt('价格');
            $table->unsignedInteger('stock')->commemt('库存');
            $table->string('image')->commemt('封面图');
            $table->string('label')->nullable()->commemt('标签');
            $table->boolean('on_sale')->default(true)->commemt('是否在售');
            $table->unsignedInteger('sold_count')->default(0)->commemt('销售总数');
            $table->unsignedInteger('review_count')->default(0)->commemt('浏览数');
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
        Schema::dropIfExists('products');
    }
}
