@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">

        {{-- Banner Component --}}
        {!! $banner->render() !!}

        <div class="login col-lg-12">
            <div class="col-lg-12">
                <div class="row">
                    @if($errors->any())
                        <div class="login_errors">
                            <p>{{$errors->first()}}</p>
                        </div>
                    @endif

                    <div class="login_content col-lg-10 offset-lg-1">
                        <form method="POST" action="/festive-floristry/login" aria-label="{{ __('Login') }}" class="login_form login_form-auth">
                            @csrf

                            <h3 class="login_form_heading">Login</h3>
                            <input type="email" name="email" placeholder="Email address" class="login_input">
                            <input type="password" name="password" placeholder="Password" class="login_input">
                            <input type="submit" value="LOGIN" class="cta">
                            <a href="{{ route('frontend.ff.forgot-password') }}" class="login_forgot">Forgot your password?</a>
                        </form>

                        <form method="POST" action="/festive-floristry/register" class="login_form login_form-registration">
                            @csrf

                            <h3 class="login_form_heading">New registration</h3>
                            <input type="email" name="email" placeholder="Email address" class="login_input" value="{{ old('email') }}">
                            <input type="password" name="password" placeholder="Password" class="login_input" value="{{ old('password') }}">
                            <input type="text" name="company_name" placeholder="Company Name" class="login_input" value="{{ old('company_name') }}">
                            <input type="text" name="first_name" placeholder="First Name" class="login_input" value="{{ old('first_name') }}">
                            <input type="text" name="last_name" placeholder="Surname" class="login_input" value="{{ old('last_name') }}">
                            <input type="submit" value="REGISTER" class="cta">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- About Tile Component --}}
        @include('frontend.components.about')

    </div>
@endsection

@section('scripts')
@endsection
