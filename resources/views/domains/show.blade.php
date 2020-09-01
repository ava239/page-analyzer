@extends('layouts.app')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Site: {{ $domain->name }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <td>id</td>
                    <td>{{ $domain->id }}</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>{{ $domain->name }}</td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td>{{ $domain->created_at }}</td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td>{{ $domain->updated_at }}</td>
                </tr>
            </table>
        </div>
        <h2 class="mt-5 mb-3">Checks</h2>
        <form method="post" action="/domains/{{ $domain->id }}/checks">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-primary" value="Run check">
        </form>

        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>Id</th>
                <th>Status Code</th>
                <th>h1</th>
                <th>Keywords</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
            @foreach($checks as $check)
                <tr>
                    <th>{{ $check->id }}</th>
                    <th>{{ $check->status_code }}</th>
                    <th>{{ $check->h1 }}</th>
                    <th>{{ $check->keywords }}</th>
                    <th>{{ $check->description }}</th>
                    <th>{{ $check->created_at }}</th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection


