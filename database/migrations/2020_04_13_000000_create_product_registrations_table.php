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
        Schema::create('product_registrations', function (Blueprint $table) {
            $table->id();
            // Data contact
            $table->string('folio')->index();
            $table->integer('provider_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null');
            $table->string('municipality');
            $table->string('colony');
            $table->string('zip_code');
            $table->text('street');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            // Data purchase
            $table->date('date_purchase');
            $table->string('product_type');
            $table->string('serial_number')->nullable();
            $table->string('place_purchase');
            $table->string('name_place_purchase')->nullable();
            $table->string('sku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('product_registrations');
    }
};
