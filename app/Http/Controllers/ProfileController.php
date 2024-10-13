<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        // Логика для отображения профиля
        return view('profile');
    }

    public function update(Request $request)
{
    // Получаем авторизованного пользователя
    /** @var \App\Models\User $user **/
    $user = Auth::user();

    // Валидируем входящие данные
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'avatar' => 'nullable|image|max:2048', // Максимум 2MB
    ]);

    // Обновляем имя и email
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Обновляем пароль, если он указан
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    // // Обработка аватара
    // if ($request->hasFile('avatar')) {
    //     // Удаляем старый аватар, если он существует
    //     if ($user->avatar) {
    //         Storage::disk('public')->delete($user->avatar);
    //     }
    //     // Сохраняем новый аватар
    //     $path = $request->file('avatar')->store('avatars', 'public');
    //     $user->avatar = $path;

    // }

    // Обработка аватара
    if ($request->hasFile('avatar')) {
        // Получаем содержимое файла как бинарные данные
        $avatarContents = file_get_contents($request->file('avatar')->getRealPath());
        $user->avatar = $avatarContents; // Храним бинарные данные
    }

    // Сохраняем изменения пользователя
    try {
        $user->save();  // Здесь метод save() должен сработать
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Произошла ошибка при сохранении: ' . $e->getMessage()]);
    }

    return redirect()->route('profile')->with('success', 'Данные успешно обновлены');
}

public function destroyAvatar(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Удаляем аватар (устанавливаем поле в null)
    $user->avatar = null;
    $user->save();

    return redirect()->route('profile')->with('success', 'Аватар успешно удалён.');
}
}