<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // public function showLoginForm()
    // {
    //     return view('auth.login');
    // }

    protected function login(Request $request)
    {
        // return redirect()->intended($this->redirectTo);

        // Валидация ввода пользователя
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Попытка авторизации
        if (Auth::attempt($request->only('email', 'password'))) {
            // Успешная авторизация
            return redirect()->intended($this->redirectTo);
        }

        // Если авторизация не удалась
        throw ValidationException::withMessages([
            'email' => ['Неверный логин или пароль.'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
