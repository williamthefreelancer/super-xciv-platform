@extends('layouts.app')

@section('title', 'Join | ' . config('app.name'))

@section('content')
    <div class="page-wrap">
        <section class="page-section form-page form-narrow" aria-labelledby="register-title">
            <p class="eyebrow"><span class="eyebrow-line" aria-hidden="true"></span> Join the network</p>
            <h1 class="page-title" id="register-title">Join XCIV</h1>

            <form class="form" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="name">Your name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="organization_name">Venue or company</label>
                    <input id="organization_name" name="organization_name" type="text" value="{{ old('organization_name') }}" required>
                    @error('organization_name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="sector">Your world</label>
                        <select id="sector" name="sector" required>
                            <option value="" disabled @selected(! old('sector'))>Choose one</option>
                            @foreach ($sectors as $value => $label)
                                <option value="{{ $value }}" @selected(old('sector') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('sector')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="city">City</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}" required>
                        @error('city')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                </div>

                <button class="button" type="submit">Create account</button>
                <p class="form-note">Already a member? <a href="{{ route('login') }}">Log in</a></p>
            </form>
        </section>
    </div>
@endsection
