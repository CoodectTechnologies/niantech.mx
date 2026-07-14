<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Foreign
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('product_brand_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('shipping_class_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null')->onUpdate('set null');
            $table->foreignId('unit_type_id')->nullable()->constrained()->onDelete('set null');

            // General
            $table->string('sku')->index()->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->index()->nullable();
            $table->text('name');
            $table->string('name_commercial')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->decimal('price_promotion')->nullable();
            $table->text('slug');
            $table->longText('detail')->nullable();
            $table->longText('description')->nullable();
            $table->text('search_advanced')->nullable();
            $table->boolean('featured')->nullable()->default(false);
            $table->enum('status', [Product::STATUS_PUBLISHED, Product::STATUS_DRAFT])->default(Product::STATUS_PUBLISHED);
            $table->text('iframe_url')->nullable();
            $table->text('technical_datasheet')->nullable();
            $table->enum('type', [Product::TYPE_PHYSICAL, Product::TYPE_DIGITAL, Product::TYPE_PHYSICAL_AND_DIGITAL])->default(Product::TYPE_PHYSICAL);
            $table->text('file_digital')->nullable();
            $table->boolean('downloadable')->nullable()->default(false);

            // Marketplace
            $table->text('link_amazon')->nullable();
            $table->text('link_mercadolibre')->nullable();

            // Shipping
            $table->float('weight_kl')->nullable();
            $table->float('height')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();
            $table->float('volume')->nullable();

            // Metatags
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('products');
    }
}
