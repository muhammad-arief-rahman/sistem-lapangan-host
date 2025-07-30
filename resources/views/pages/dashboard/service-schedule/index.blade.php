@extends('layout.dashboard')

@section('title', 'Jadwal')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Jadwal' => route('dashboard.service-schedule.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Jadwal" value="{{ $cardData->totalSchedules }}">
                <x-slot:icon>
                    <i class="fa-solid fa-calendar"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Jadwal Aktif" value="{{ $cardData->totalActiveSchedules }}">
                <x-slot:icon>
                    <i class="fa-solid fa-check"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Jadwal Tidak Aktif" value="{{ $cardData->totalInactiveSchedules }}">
                <x-slot:icon>
                    <i class="fa-solid fa-times"></i>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium">Jadwal Jasa</h2>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('dashboard.service-schedule.index', [
                        'view' => 'table',
                    ]) }}"
                        class="btn btn-icon {{ request()->get('view') === 'table' ? 'btn-primary' : 'btn-ghost' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-table2-icon lucide-table-2">
                            <path
                                d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18" />
                        </svg>
                    </a>
                    <a href="{{ route('dashboard.service-schedule.index', [
                        'view' => 'calendar',
                    ]) }}"
                        class="btn btn-icon {{ request()->get('view', 'calendar') === 'calendar' ? 'btn-primary' : 'btn-ghost' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                    </a>
                    <button data-modal="modal-create" class="btn btn-icon ml-2 btn-primary">
                        <i class="fa-solid fa-plus text-lg"></i>
                    </button>
                </div>
            </div>

            @if (request()->get('view') === 'table')
                <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                    @include('partials.service-schedule-table', ['schedules' => $schedules])
                </div>
            @else
                @if (authorized('referee', 'photographer'))
                    <div>
                        <p class="text-sm text-zinc-500 mb-2">
                            Anda ingin membuat jadwal diluar sistem? Silahkan klik pada tanggal yang diinginkan pada
                            kalender
                            dibawah ini. <br>
                        </p>
                    </div>
                @endif

                @include('partials.service-schedule-calendar', ['schedules' => $schedules])
            @endif
        </div>
    </div>
@endsection

@include('partials.schedule-create-modal')
