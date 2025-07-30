@extends('layout.dashboard')

@section('title', 'Penarikan')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Penarikan' => route('dashboard.withdrawal.index')]" />
        <div class="dashboard-cards">
            @if (auth()->user()->role !== 'super_admin')
                <x-dashboard-card title="Saldo" value="{{ format_rp($cardData->balance) }}">
                    <x-slot:icon>
                        <i class="fa-solid fa-wallet"></i>
                    </x-slot:icon>
                </x-dashboard-card>
            @endif
            <x-dashboard-card title="Total Penarikan" value="{{ $cardData->totalWithdrawals }}">
                <x-slot:icon>
                    <i class="fa-solid fa-receipt"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Penarikan Menunggu" value="{{ format_rp($cardData->pendingWithdrawalAmount) }}">
                <x-slot:icon>
                    <i class="fa-solid fa-clock"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Penarikan Berhasil" value="{{ $cardData->completedWithdrawal }}">
                <x-slot:icon>
                    <i class="fa-solid fa-check"></i>
                </x-slot:icon>
            </x-dashboard-card>

        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4 justify-between">
                <h2 class="text-lg font-medium ">Daftar Penarikan</h2>
                @if (auth()->user()->role !== 'super_admin')
                    <button data-modal="withdrawal-modal" class="btn btn-primary">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Tarik</span>
                    </button>
                @endif
            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                @include('partials.withdrawal-table', ['withdrawals' => $withdrawals])
            </div>
        </div>
    </div>
@endsection

@if (auth()->user()->role !== 'super_admin')
    @include('partials.withdrawal.withdrawal-modal')
@endif



@push('scripts')
    <script>
        document.querySelector('#max-amount-button').addEventListener('click', function() {
            document.getElementById('amount').value = {{ $cardData->balance }}
        });
    </script>
@endpush
