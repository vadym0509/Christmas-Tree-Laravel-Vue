@extends('layouts.base')

@section('css')
@endsection

@section('content')
    <div class="col-lg-12">

        <div class="login col-lg-12">
            <div class="col-lg-12">
                <div class="row">
                    @if($errors->any())
                        <div class="login_errors">
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @elseif($message = session()->get('message'))
                        <div class="login_errors">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="login_content col-lg-10 offset-lg-4">
                        <form method="POST" action="/festive-floristry/forgot-password" aria-label="{{ __('Forgot Password') }}" class="login_form login_form-auth">
                            @csrf

                            <h3 class="login_form_heading">Forgot Password</h3>
                            <input type="email" name="email" placeholder="Email address" class="login_input">
                            <input type="submit" value="RESET PASSWORD" class="cta">
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
