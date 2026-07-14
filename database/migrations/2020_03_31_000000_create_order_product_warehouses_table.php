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
        Schema::create('order_product_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_product_id')->nullable();
            $table->foreign('order_product_id')->references('id')->on('order_product')->onDelete('cascade');
            $table->foreignId('product_warehouse_id')->nullable()->constrained()->onDelete('set null');
            $table->string('quantity')->nullable();
            $table->boolean('apply_provider')->default(false);
            $table->string('provider')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_product_warehouses');
    }
};
