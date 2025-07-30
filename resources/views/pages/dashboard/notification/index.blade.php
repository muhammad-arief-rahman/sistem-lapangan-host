@extends('layout.dashboard')

@section('title', 'Notifikasi')

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Notifikasi" value="{{ $cardData->totalNotifications }}">
                <x-slot:icon>
                    <i class="fa-solid fa-bell"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Belum Dibaca" value="{{ $cardData->unreadNotifications }}">
                <x-slot:icon>
                    <i class="fa-solid fa-bell-slash"></i>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4 justify-between">
                <h2 class="text-lg font-medium ">Daftar Notifkasi</h2>

                <form action="{{ route('dashboard.notification.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i>
                        <span>Baca Semua</span>
                    </button>
                </form>

            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Pesan</th>
                            <th>Dibaca Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr class="[&>td]:whitespace-nowrap {{ $notification->read_at ? '' : 'bg-primary/5' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $notification->data['title'] }}</td>
                                <td class="!whitespace-normal min-w-sm">{{ $notification->data['message'] }}</td>
                                <td>
                                    @if ($notification->read_at)
                                        {{ $notification->read_at->translatedFormat('l, d F Y, H:i') }}
                                    @else
                                        <span class="badge badge-warning">Belum Dibaca</span>
                                    @endif
                                </td>
                                <td class="flex items-center gap-2">
                                    @if (!$notification->read_at)
                                        <form
                                            action="{{ route('dashboard.notification.read', ['id' => $notification->id]) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                Baca
                                            </button>
                                        </form>
                                    @endif
                                    @if (isset($notification->data['action_url']) && URL::isValidUrl($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="btn btn-secondary">
                                            Lihat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
