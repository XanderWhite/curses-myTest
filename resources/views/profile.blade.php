@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Редактирование профиля</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Электронная почта:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', auth()->user()->email) }}" required>
                @if ($errors->has('email'))
                    <small class="text-danger">{{ $errors->first('email') }}</small>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8"
                    placeholder="Минимум 8 символов">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    minlength="8">
            </div>

            <div class="form-group">
                <label for="avatar" class="avatar-label">Изменить аватар:
                    @if (auth()->user()->avatar)
                    <img class="profile-avatar" src="data:image/jpeg;base64,{{ base64_encode(auth()->user()->avatar) }}"
                        alt="Avatar" />
                @else
                    <img class="profile-avatar" src="images/avatar-default.png" alt="Default Avatar"/>
                @endif
                </label>
                <input style="display: none" type="file"  class="form-control-file" id="avatar" name="avatar" accept="image/*">
                <br>

            </div>

            @if (auth()->user()->avatar)
                <form action="{{ route('profile.avatar.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить аватар</button>
                </form>
            @endif

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endsection
