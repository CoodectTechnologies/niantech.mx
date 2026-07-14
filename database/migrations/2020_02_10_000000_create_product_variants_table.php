<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->index();
            $table->string('variant_key')->index(); // color:rojo|size:s

            // Datos únicos de la variante
            $table->string('sku')->nullable()->index(); // PROD-001-RED-S
            $table->decimal('price', 12, 2);
            $table->decimal('price_promotion', 12, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();

            // Dimensiones
            $table->decimal('weight_kl', 8, 3)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();

            // Control
            $table->unsignedInteger('position')->default(0); // Para ordenar
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('product_variants');
    }
};
