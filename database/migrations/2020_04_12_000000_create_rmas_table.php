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
        Schema::create('rmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('folio')->index();
            $table->integer('provider_id')->index();
            $table->integer('failure_type_provider_id');
            $table->string('email')->index();
            $table->string('name');
            $table->string('phone');
            $table->string('order_number');
            $table->string('sku');
            $table->string('serial_number');
            $table->text('observation');
            $table->string('zip_code');
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rmas');
    }
};
