<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
        {{ "Kilted Christmas Tree" }}
        @if (Request::path() != "/")
            {{ " | ".ucfirst(collect(Request::segments())->last()) }}
        @endif
        </title>

        <meta name="keywords" content="UK Kilted Christmas Tree {{ ucfirst(collect(Request::segments())->last()) }}">

        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-100123451-8"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-100123451-8');
        </script>

        @include('includes.css')
    </head>
    <body class="{{ ff() ? 'ff-theme' : '' }}">
        <div id="app">
            @include('includes.header')

            @if($toast = request()->session()->get('toast'))
                <session-message :toast="{{ $toast }}"></session-message>
            @endif

            <div class="container content">
                <div class="row">
                    @yield('content')
                </div>
            </div>
            @include('includes.footer')
        </div>
        @include('includes.scripts')
    </body>
</html>
