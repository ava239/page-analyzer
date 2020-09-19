@extends('layouts.app')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">{{ __('domains') }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>{{ __('id') }}</th>
                    <th>{{ __('name') }}</th>
                    <th>{{ __('last_check') }}</th>
                    <th>{{ __('check_status') }}</th>
                    <th>{{ __('status_code') }}</th>
                </tr>
                @foreach($domains as $domain)
                    <tr>
                        <th>{{ $domain->id }}</th>
                        <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
                        <td>{{ $checkResultsByDomain->get($domain->id)['created_at'] }}</td>
                        <td>{{ __(sprintf('domain.check_status.%s', $checkResultsByDomain->get($domain->id)['state'])) }}</td>
                        <td>{{ $checkResultsByDomain->get($domain->id)['status_code'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection


