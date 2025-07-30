@extends('layout.dashboard')

@section('title', 'Dashboard')

@php
    $role = auth()->user()->role;
@endphp

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs />

        @if ($role === 'super_admin')
            @include('partials.dashboard.admin')
        @elseif ($role === 'field_manager')
            @include('partials.dashboard.field-manager')
        @elseif ($role === 'photographer' || $role === "referee")
            @include('partials.dashboard.services')
        @else
            @include('partials.dashboard.community')
        @endif
    </div>
@endsection
