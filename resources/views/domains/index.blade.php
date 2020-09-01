@extends('layouts.app')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Domains</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last check</th>
                    <th>Status Code</th>
                </tr>
                @foreach($domains as $domain)
                    <tr>
                        <th>{{ $domain->id }}</th>
                        <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
                        <td>{{ $domain->last_check }}</td>
                        <td>{{ $domain->status_code }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection


