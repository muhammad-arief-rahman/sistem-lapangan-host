@extends('layout.dashboard')

@section('title', 'Booking')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Booking' => route('dashboard.booking.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Booking" value="{{ $cardData->totalBookings }}">
                <x-slot:icon>
                    <i class="fa-solid fa-receipt"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Open Matches" value="{{ $cardData->totalOpenMatches }}">
                <x-slot:icon>
                    <i class="fa-solid fa-futbol"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Trofeos" value="{{ $cardData->totalTrofeos }}">
                <x-slot:icon>
                    <i class="fa-solid fa-trophy"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Regular Bookings" value="{{ $cardData->totalRegularBookings }}">
                <x-slot:icon>
                    <i class="fa-solid fa-calendar"></i>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-lg font-medium ">Daftar Booking</h2>
                @if (authorized('community'))
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus "></i>
                        <span>
                            Tambah Booking
                        </span>
                    </a>
                @endif
            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                @include('partials.booking-table', ['bookings' => $bookings])
            </div>
        </div>
    </div>
@endsection
