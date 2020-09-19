@extends('layouts.app')
@section('content')
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3">{{ __('app_title') }}</h1>
                    <p class="lead">{{ __('app_description') }}</p>
                    <form action="{{ route('domains.store') }}" method="post" class="d-flex justify-content-center">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="text" name="domain[name]" value="{{ old('domain.name') }}"
                               class="form-control form-control-lg" placeholder="https://www.example.com">
                        <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">{{ __('check') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
