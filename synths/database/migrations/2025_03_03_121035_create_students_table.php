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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('cin', 40)->unique();
            $table->string('s_fname', 100);
            $table->string('s_lname', 100);
            $table->string('id_card_img')->nullable();
            $table->string('bac_img')->nullable();
            $table->string('birth_img')->nullable();
            $table->string('code_class', 50);
            $table->string('filier_name', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
