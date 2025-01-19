<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempReview extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно отличается от стандартного
    protected $table = 'temp_reviews';

    // Укажите, какие поля можно массово заполнять
    protected $fillable = [
        'text',
        'date',
        'name',
        'raiting',
        'course',
        'url_course',
        'school',
        'about',
        'password',
        'email'
    ];

    // Если 'id' является автоинкрементным полем, то его не нужно указывать в $fillable
    // Также можно указать, если 'timestamps' не используются
    public $timestamps = false; // Установите true, если используете timestamps
}
