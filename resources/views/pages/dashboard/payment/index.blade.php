@extends('layout.dashboard')

@section('title', 'Pembayaran')

@php
    $bookingId = request()->input('booking_id');
@endphp

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Pembayaran' => route('dashboard.payment.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Pembayaran" value="{{ $cardData->totalPayments }}">
                <x-slot:icon>
                    <i class="fa-solid fa-money-bill"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Menunggu Pembayaran" value="{{ $cardData->totalPendingPayments }}">
                <x-slot:icon>
                    <i class="fa-solid fa-clock"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Selesai" value="{{ $cardData->totalCompletedPayments }}">
                <x-slot:icon>
                    <i class="fa-solid fa-check-circle"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Gagal" value="{{ $cardData->totalFailedPayments }}">
                <x-slot:icon>
                    <i class="fa-solid fa-times-circle"></i>
                </x-slot:icon>
            </x-dashboard-card>

        </div>
        <div class="flex flex-col gap-4">
            <h2 class="text-lg font-medium ">Daftar Pembayaran</h2>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                @include('partials.payment-details-table', ['paymentDetails' => $paymentDetails])
            </div>
        </div>
    </div>
@endsection
