<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token"/>

    <title>{{ __('app_title') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column">
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('welcome') }}">{{ __('nav_title') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link @if(Route::is('welcome')) active @endif" href="{{ route('welcome') }}">{{ __('home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Route::is('domains.index')) active @endif" href="{{ route('domains.index') }}">{{ __('domains') }}</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-grow-1">
    @include('flash::message')
    @yield('content')
</main>

<footer class="border-top py-3 mt-5">
    <div class="container-lg">
        <div class="text-center">
            {{ __('created_by') }}
            <a href="https://github.com/ava239" target="_blank">Ava</a>
        </div>
    </div>
</footer>
</body>
</html>
