<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_web_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('order');
            $table->jsonb('title')->nullable();
            $table->jsonb('subtitle')->nullable();
            $table->jsonb('description')->nullable();
            $table->string('btn_url')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('video')->nullable();
            $table->enum('type', ['Imagen', 'Video'])->default('Imagen');
            $table->string('color')->nullable()->default('#000');
            $table->boolean('overlay')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('banners');
    }
}
