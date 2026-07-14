<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable();
            $table->string('provider_id')->index()->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->foreign('billing_address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->string('number')->nullable();
            $table->float('shipping_price', 12)->comment('Sin iva');
            $table->float('shipping_price_tax', 12)->default(0)->comment('Iva del precio de envío');
            $table->float('shipping_price_final', 12)->comment('Precio de envío con impuesto');
            $table->string('shipping_method')->nullable();
            $table->integer('provider_shipping_method_id')->nullable();
            $table->string('shipping_days')->nullable();
            $table->float('coupon_price_discount', 12)->nullable();
            $table->float('coupon_percentage_discount', 12)->nullable();
            $table->float('subtotal', 12)->comment('Sin iva');
            $table->float('subtotal_tax', 12)->default(0)->comment('Impuesto del subtotal');
            $table->float('subtotal_final', 12)->comment('Subtotal con impuesto');
            $table->float('tax', 12)->default(0)->comment('Impuesto total');
            $table->float('total', 12);
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->json('payment_data')->nullable();
            $table->enum('payment_status', [Order::PAYMENT_STATUS_APPROVED, Order::PAYMENT_STATUS_PENDING, Order::PAYMENT_STATUS_REJECTED])->default(Order::PAYMENT_STATUS_PENDING);
            $table->enum('status', [Order::STATUS_CONFIRMED, Order::STATUS_PROCESSING, Order::STATUS_SENT, Order::STATUS_COMPLETED, Order::STATUS_CANCELED, Order::STATUS_REFUND])->default(Order::STATUS_CONFIRMED);
            $table->string('currency');
            $table->double('currency_value')->nullable()->default(1.0000);
            $table->double('require_billing')->default(false);
            $table->boolean('send_email')->nullable()->default(false);
            $table->longText('send_email_error')->nullable();
            $table->boolean('send_email_track')->nullable()->default(false);
            $table->longText('send_email_track_error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
}
