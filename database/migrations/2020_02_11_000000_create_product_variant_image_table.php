<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variant_image', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('image_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->primary(['product_variant_id', 'image_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_image');
    }
};
