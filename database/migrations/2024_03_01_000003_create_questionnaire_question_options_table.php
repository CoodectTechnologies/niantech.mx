<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnaireQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('questionnaire_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_positive')->default(false)->comment('Si esta opción cuenta como respuesta positiva para aptitud');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('questionnaire_question_options');
    }
}
