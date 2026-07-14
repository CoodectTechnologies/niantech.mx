<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade'); // Variante de producto
            $table->foreignId('product_option_value_id')->constrained('product_option_values')->onDelete('cascade'); // "Rojo", "S", "Algodón"

            // Búsquedas rápidas
            $table->index('product_variant_id');
            $table->index('product_option_value_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('product_variant_options');
    }
};
