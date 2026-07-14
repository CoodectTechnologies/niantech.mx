<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_warehouse_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->text('provider_id_data')->nullable();
            $table->text('provider_guide')->nullable();
            $table->text('provider_guide_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_providers');
    }
};
