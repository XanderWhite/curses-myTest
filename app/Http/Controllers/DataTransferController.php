<?php

namespace App\Http\Controllers;

use App\Models\TempReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Jobs\HashPasswordJob;

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

    public function hashPassword()
    {
        // Получаем все записи из temp_reviews
        $users = User::all();

        // foreach ($users as $user) {

        //     $user->password = Hash::make($user->password);

        //     $user->save();

        // }

        // foreach ($users as $user) {
        //     DB::table('users')
        //         ->where('id', $user->id)
        //         ->update(['password' => Hash::make($user->password)]);
        // }

        // Получаем все записи из temp_reviews
    $users = User::all();

    // Создаем задачу для каждого пользователя
    foreach ($users as $user) {
        HashPasswordJob::dispatch($user);
    }

        return response()->json([ 'ok'], 201);


    }
}



namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $this->user->password = Hash::make($this->user->password);
        $this->user->save();
    }
}