<?php

namespace App\Http\Controllers;

use App\Models\TempReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataTransferController extends Controller
{
    public function transferData()
    {
        // Получаем все записи из temp_reviews
        $tempReviews = TempReview::all();
        // whereBetween('id', [5001, 5500])->get(); //по 500

        foreach ($tempReviews as $review) {

            if(!User::where('email', $review->email)->exists())

            // Создаем нового пользователя
            User::create([
                'name' => $review->name, // Предполагается, что в temp_reviews есть поле name
                'email' => $review->email, // Предполагается, что в temp_reviews есть поле email
                'password' => Hash::make($review->password), // Хэшируем пароль

                // 'remember_token' => null, // Или установите его, если нужно
                // 'role_id' => 1, // Установите роль по умолчанию
                // 'created_at' => now(), // Установите дату создания
                // 'updated_at' => now(), // Установите дату обновления
            ]);
        }

        return response()->json([ DB::table('users')->count()], 201);

 
    }
}
