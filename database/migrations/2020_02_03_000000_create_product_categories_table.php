<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->string('key_product_or_service')->nullable();
            $table->string('provider')->nullable();

            $table->text('name');
            $table->text('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('include_in_menu')->default(true);
            $table->integer('order')->nullable();
            $table->boolean('status')->default(true);

            // Metatags
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('product_categories');
    }
}
