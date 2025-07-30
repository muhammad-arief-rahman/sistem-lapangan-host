@extends('layout.dashboard')

@section('title', 'Foto Pertandingan')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Foto Pertandingan' => route('dashboard.match-photos.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Pertandingan" value="{{ $cardData->totalMatches }}">
                <x-slot:icon>
                    <i class="fa-solid fa-futbol"></i>
                </x-slot:icon>
            </x-dashboard-card>

        </div>
        <div class="flex flex-col gap-4">
            <h2 class="text-lg font-medium ">Daftar Pertandingan</h2>

            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lapangan</th>
                            <th>Pemesan</th>
                            <th>Selesai Pada</th>
                            <th>Link Tambahan</th>
                            <th>Jumlah Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr class="[&>td]:whitespace-nowrap">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->field->name }}</td>
                                <td>{{ $booking->user->name ?? $booking->user->email }}</td>
                                <td>{{ $booking->fieldSchedule->end_datetime->translatedFormat('l, d F Y, H:i') }}</td>
                                <td>
                                    @if ($booking->match_photo_link)
                                        <a href="{{ $booking->match_photo_link }}" target="_blank"
                                            class="text-blue-500 hover:underline max-w-[200px] overflow-hidden text-ellipsis">
                                            {{ $booking->match_photo_link }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 italic">Tidak ditambahkan</span>
                                    @endif
                                </td>
                                <td>{{ $booking->photos->count() }}</td>
                                <td class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.match-photos.show', $booking->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Lihat Foto</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
