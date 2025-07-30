@extends('layout.dashboard')

@section('title', 'Pendapatan')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Pendapatan' => route('dashboard.mutation.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Saldo" value="{{ format_rp($cardData->balance) }}">
                <x-slot:icon>
                    <i class="fa-solid fa-money-bill"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Mutasi" value="{{ $cardData->mutations }}">
                <x-slot:icon>
                    <i class="fa-solid fa-arrow-up"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Pendapatan" value="{{ format_rp($cardData->income) }}">
                <x-slot:icon>
                    <i class="fa-solid fa-coins"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Pendapatan Bulan Ini" value="{{ format_rp($cardData->monthly_income) }}">
                <x-slot:icon>
                    <i class="fa-solid fa-calendar"></i>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4 justify-between">
                <h2 class="text-lg font-medium ">Daftar Pendapatan</h2>
                <a href="{{ route('dashboard.withdrawal.index') }}"
                    class="inline-flex cursor-pointer items-center gap-2 text-sm text-white bg-primary hover:bg-primary/90 transition-colors rounded-lg px-4 py-2">
                    Penarikan Saldo
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Sumber</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mutations as $mutation)
                            <tr class="[&>td]:whitespace-nowrap">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ format_rp($mutation->amount) }}</td>
                                <td>{{ $mutation->created_at->translatedFormat('d/m/Y H:i') }}</td>
                                <td>{{ $mutation->source }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
