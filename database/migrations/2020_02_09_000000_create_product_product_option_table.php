<?php

use App\Models\ProductOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_product_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->string('type')->default(ProductOption::TYPE_BUTTON); // "select", "button", "color", "image"
            $table->integer('position')->default(1);
            $table->timestamps();

            $table->unique(['product_id', 'product_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('product_option_values');
    }
};
