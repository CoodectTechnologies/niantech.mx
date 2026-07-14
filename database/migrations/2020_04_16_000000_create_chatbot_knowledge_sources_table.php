<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('chatbot_knowledge_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('chatbot_id')->constrained('chatbots')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->string('path');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('status_message')->nullable();
            $table->longText('extracted_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('chatbot_knowledge_sources');
    }
};
