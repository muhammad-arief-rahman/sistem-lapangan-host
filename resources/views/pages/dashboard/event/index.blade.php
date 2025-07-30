@extends('layout.dashboard')

@section('title', 'Pertandingan')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Pertandingan' => route('dashboard.events.index')]" />
        <div class="dashboard-cards">

            <x-dashboard-card title="Total Open Matches" value="{{ $cardData->totalOpenMatches }}">
                <x-slot:icon>
                    <i class="fa-solid fa-users"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Total Trofeos" value="{{ $cardData->totalTrofeos }}">
                <x-slot:icon>
                    <i class="fa-solid fa-trophy"></i>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <h2 class="text-lg font-medium">Daftar Pertandingan Open Match & Trofeo</h2>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Komunitas</th>
                            <th>Judul Pertandingan</th>
                            <th>Deskripsi</th>
                            <th>Jadwal Pertandingan</th>
                            <th>Pemain</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventData as $event)
                            <tr class="[&>td]:whitespace-nowrap">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $event->booking->user->name ?? $event->booking->user->email }}</td>
                                <td>{{ $event->match_name }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->booking->fieldSchedule->getScheduleDateString() }}</td>
                                <td>{{ $event->getPlayerString() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
