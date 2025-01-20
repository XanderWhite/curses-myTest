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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
            // $table->date('date')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
            $table->boolean('is_approved')->default(false);  // Булево поле со значением по умолчанию false
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Указываем кодировку и collation для всей таблицы
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
