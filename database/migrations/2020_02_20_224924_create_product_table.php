<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('price');
            $table->string('details');
            $table->longText('description');
            $table->integer('quantity');
            $table->integer('rating');
            $table->boolean('is_available')->default(1);
            $table->boolean('is_new')->default(1);
            $table->boolean('is_sale')->default(0);
            $table->integer('price_sale')->nullable();
            $table->string('image');
            $table->unsignedBigInteger('category_id');
            $table->string('size')->nullable();
            $table->string('color')->nullable();

            $table->foreign('category_id')->references('id')->on('category')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('product');
    }
}
