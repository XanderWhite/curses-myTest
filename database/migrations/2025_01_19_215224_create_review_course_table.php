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
        Schema::create('review_course', function (Blueprint $table) {
             // Внешний ключ для таблицы reviews
             $table->foreignId('review_id')
             ->constrained('reviews')  // Ссылается на таблицу reviews
             ->onUpdate('cascade')
             ->onDelete('cascade');   

       // Внешний ключ для таблицы courses
       $table->foreignId('course_id')
             ->constrained('courses')  // Ссылается на таблицу schools
             ->onUpdate('cascade')
             ->onDelete('cascade');

       // Уникальный составной ключ (чтобы избежать дублирования связей)
       $table->unique(['review_id', 'course_id']);

       // Дополнительные поля (если нужно)
       // $table->timestamps();  // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_course');
    }
};
