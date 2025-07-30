@extends('layout.dashboard')

@section('title', 'Profil')

@php
    $isService = in_array(auth()->user()->role, ['referee', 'photographer']);
@endphp

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Profil' => route('dashboard.profile.index')]"></x-breadcrumbs>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-1 flex flex-col gap-4">
                @if ($isService)
                    <div class="flex justify-between items-center ">
                        <h2 class="text-lg font-medium">Detail Profil</h2>
                    </div>
                @endif

                @include('partials.profile.profile-area')

                @if ($isService)
                    @include('partials.profile.profile-edit-dropdown')
                @endif
            </div>

            <div class="md:col-span-2 flex flex-col gap-4">
                @if ($isService)
                    <div class="flex justify-between items-center ">
                        <h2 class="text-lg font-medium">Detail Layanan</h2>
                    </div>
                    @include('partials.profile.service-area')
                    @include('partials.profile.service-edit-dropdown')
                @else
                    @include('partials.profile.profile-edit-dropdown')
                @endif
            </div>
        </div>
    </div>
@endsection
