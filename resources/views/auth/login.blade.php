@extends('layouts.app')

@section('title', 'Log in | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page form-narrow" aria-labelledby="login-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Welcome back</p>
            <h1 class="page-title" id="login-title">Log in</h1>

            <form class="form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <label class="checkbox"><input type="checkbox" name="remember" value="1"> Keep me logged in</label>

                <button class="button" type="submit">Log in</button>
                <p class="form-note">New here? <a href="{{ route('register') }}">Join XCIV</a></p>
            </form>
        </section>
    </div>
@endsection
