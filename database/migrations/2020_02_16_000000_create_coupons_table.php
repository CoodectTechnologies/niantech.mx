
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index()->unique();
            $table->integer('percentage')->nullable();
            $table->float('fixed', 12)->nullable();
            $table->date('date_end');
            $table->enum('type', ['Todos', 'Categoría', 'Marca', 'Producto', 'Curso']);
            $table->enum('conditional', ['Que no sean', 'Que sean', null])->nullable()->default(null);
            $table->enum('type_coupon', ['Fijo', 'Porcentaje']);
            $table->float('minimum_expense', 12)->nullable(); // De ser nulo no implica un minimo de gasto
            $table->boolean('exclude_promotion')->default(true);
            $table->integer('limit_of_use')->nullable(); // De ser nulo, no tendra limite
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('coupons');
    }
}
