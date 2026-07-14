<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('subtitle')->nullable();
            $table->string('stripe_id')->index()->nullable();
            $table->string('stripe_product_name')->nullable();
            $table->string('stripe_price_month_id')->index()->nullable();
            $table->string('stripe_price_year_id')->index()->nullable();
            $table->float('amount_month');
            $table->float('amount_year');
            $table->integer('free_trial_days')->default(0)->nullable();
            $table->boolean('status')->default(true)->nullable();
            $table->boolean('featured')->default(false)->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('plans');
    }
};
