<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewSchoolTable extends Migration
{
    public function up()
    {
        // Создаем сводную таблицу
        Schema::create('review_school', function (Blueprint $table) {
            // Внешний ключ для таблицы reviews
            $table->foreignId('review_id')
                  ->constrained('reviews')  // Ссылается на таблицу reviews
                  ->onUpdate('cascade')
                  ->onDelete('cascade');    // Каскадное удаление

            // Внешний ключ для таблицы schools
            $table->foreignId('school_id')
                  ->constrained('schools')  // Ссылается на таблицу schools
                  ->onUpdate('cascade')
                  ->onDelete('cascade');    // Каскадное удаление

            // Уникальный составной ключ (чтобы избежать дублирования связей)
            $table->unique(['review_id', 'school_id']);

            // Дополнительные поля (если нужно)
            // $table->timestamps();  // created_at и updated_at
        });
    }

    public function down()
    {
        // Удаляем сводную таблицу при откате миграции
        Schema::dropIfExists('review_school');
    }
}