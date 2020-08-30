<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />

    <title>Laravel</title>

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
        <a class="navbar-brand" href="{{ route('welcome') }}">Analyzer</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('welcome') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('domains.index') }}">Domains</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-grow-1">
    @include('flash::message')
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3">Page Analyzer</h1>
                    <p class="lead">Check web pages for free</p>
                    <form action="{{ route('domains.store') }}" method="post" class="d-flex justify-content-center">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="text" name="domain[name]" value="{{ old('domain.name') }}" class="form-control form-control-lg" placeholder="https://www.example.com">
                        <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="border-top py-3 mt-5">
    <div class="container-lg">
        <div class="text-center">
            created by
            <a href="https://github.com/ava239" target="_blank">Ava</a>
        </div>
    </div>
</footer>
</body>
</html>
