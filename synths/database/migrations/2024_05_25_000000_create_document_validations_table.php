<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->string('cin');
            $table->string('student_name');
            $table->string('verified_name')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->json('file_details')->nullable();
            $table->json('errors')->nullable();
            $table->string('filier_name');
            $table->string('class_name');
            $table->timestamp('validation_date')->useCurrent();
            $table->timestamps();

            // Index for faster lookups
            $table->index('cin');
            $table->index(['filier_name', 'class_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_validations');
    }
};
