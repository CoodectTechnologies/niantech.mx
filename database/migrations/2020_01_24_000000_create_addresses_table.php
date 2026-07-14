<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('use_cfdi_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('fiscal_regime_id')->nullable()->constrained()->onDelete('set null');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('municipality');
            $table->string('colony');
            $table->string('zip_code');
            $table->text('street');
            $table->text('street_between')->nullable();
            $table->text('street_references')->nullable();
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('vat')->nullable()->comment('RFC');
            $table->boolean('is_default')->nullable()->default(false);
            $table->boolean('is_billing')->nullable()->default(false);
            $table->boolean('is_billing_default')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('addresses');
    }
}
