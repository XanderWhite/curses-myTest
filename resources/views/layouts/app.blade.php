<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Courses')</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body>
    {{-- Главное меню страницы --}}
    <header class="header">
        <nav class="header__nav">
            <ul class="menu">
                <li class="menu__item">
                    <a class="menu__link" href="{{ route('home') }}">Главная</a>
                </li>
                @guest
                    <li class="menu__item"><button id="loginBtn" class="btn dialogBtn">Авторизация</button></li>
                    <li class="menu__item"><button id="registerBtn" class="btn dialogBtn">Регистрация</button></li>
                @else
                    <li class="menu__item">
                        <a class="menu__link menu__link_lk" href="{{ route('profile') }}">
                            @if (Auth::user()->avatar)
                                <img class="menu-avatar"
                                    src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->avatar) }}" alt="Avatar" />
                            @else
                                <img class="menu-avatar" src="images/avatar-default.png" alt="Default Avatar" />
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="menu__item">
                        <form class="menu__form" action="{{ route('logout') }}" method="POST" ">
                                    @csrf
                                    <button class="btn" type="submit">Выход</button>
                                    </form>
                            </li>
                @endguest
            </ul>
        </nav>
    </header>

    {{-- Основной контент страницы --}}
    <main class="main">
        @yield('content')
    </main>


    {{-- ==============Модальные окна --}}

     <!-- Модальное окно регистрации -->
     <dialog id="registerModal">
        <div class="modal-content">
            <span class="close" id="closeRegisterModal">&times;</span>
            <h5>Регистрация</h5>
            <form class="modal-form modal-form_reg" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name">Имя</label>
                    <input type="text" name="name" id="nameReg" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="emailReg" required>
                </div>
                <div>
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="passwordReg" required>
                </div>
                <div>
                    <label for="password_confirmation">Подтвердите пароль</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
                <button class="modal-form__btn" type="submit">Зарегистрироваться</button>
                <p id='error-reg'></p>
            </form>
        </div>
    </dialog>

    {{-- <!-- Модальное окно авторизации --> --}}
    <dialog id="loginModal">
        <div class="modal-content">
            <span class="close" id="closeLoginModal">&times;</span>
            <h5>Авторизация</h5>
            <form class="modal-form modal-form_login" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="emailLogin" required value="{{ old('email') }}">
                                   </div>
                <div>
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="passwordLogin" required>
                                   </div>
                <button class="modal-form__btn" type="submit">Войти</button>
                <p id='error-login'></p>
            </form>
        </div>
    </dialog>




    {{-- =================JS --}}

    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
</body>

</html>


{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html> --}}
