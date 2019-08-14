@extends('admin.layouts.app')

@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.system_setting_page') }}</h2>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-responsive table-bordered">
        <th>{{ trans('admins.name_field') }}</th>
        <th>{{ trans('admins.active_field') }}</th>
        <th>{{ trans('admins.value_field') }}</th>
        @foreach (SystemSettings::getadmin() as $setting)
            <tr>
                <td>{{ $setting->name }}</td>
                <td>{{ $setting->active?trans('admins.active_on'):trans('admins.active_off') }}</td>
                <td>{{ $setting->value }}</td>
            </tr>
        @endforeach

    </table>

@endsection
