@extends('layout.dashboard')

@section('title', 'Lapangan')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Lapangan' => route('dashboard.field.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Total Lapangan" value="{{ $cardData->totalFields }}">
                <x-slot:icon>
                    <i class="fa-solid fa-flag"></i>
                </x-slot:icon>
            </x-dashboard-card>
            @if (authorized('super_admin'))
                <x-dashboard-card title="Total Pengelola Lapangan" value="{{ $cardData->totalManagers }}">
                    <x-slot:icon>
                        <i class="fa-solid fa-users"></i>
                    </x-slot:icon>
                </x-dashboard-card>
            @endif
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium ">Daftar Lapangan</h2>
                <div>
                    <button data-modal="add-field"
                        class="bg-primary text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                        Tambah Lapangan
                    </button>
                </div>
            </div>
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable  w-full ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lapangan</th>
                            <th>Alamat Lengkap</th>
                            @if (authorized('super_admin'))
                                <th>Pemilik</th>
                            @endif
                            <th>Fasilitas</th>
                            <th>Harga per Jam</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fields as $field)
                            <tr class="[&>td]:whitespace-nowrap">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $field->name }}</td>
                                <td>{{ $field->fullLocation }}</td>
                                @if (authorized('super_admin'))
                                    <td>{{ $field->manager->name }}</td>
                                @endif
                                <td>
                                    <ul class="list-disc pl-4 max-h-20 pr-2 overflow-y-auto">
                                        @forelse ($field->facilities as $facility)
                                            <li class="text-sm">{{ $facility->name }}</li>
                                        @empty
                                            <li class="text-sm text-zinc-500">Tidak ada fasilitas</li>
                                        @endforelse
                                    </ul>
                                </td>
                                <td>{{ format_rp($field->price_per_hour) }}</td>
                                <td>
                                    <a href="{{ $field->imageUrl }}" target="_blank"
                                        class="hover:brightness-90 duration-100">
                                        <img src="{{ $field->imageUrl }}" alt="lapangan-{{ $field->name }}"
                                            class="rounded-md object-cover aspect-video min-w-32 shrink-0 bg-zinc-200">
                                    </a>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 h-full">
                                        @if (authorized('field_manager'))
                                            <button class="btn btn-primary btn-sm" data-modal="field-schedule-modal"
                                                data-field-data='@json($field)'>
                                                Jadwal
                                            </button>
                                        @endif
                                        @if (authorized('field_manager', 'super_admin'))
                                            <button data-modal="edit-field-{{ $field->id }}"
                                                class="btn btn-primary btn-sm">
                                                Edit
                                            </button>

                                            <x-field-modal :field="$field" id="edit-field-{{ $field->id }}" />
                                        @endif
                                        @if (authorized('super_admin', 'field_manager'))
                                            <form action="{{ route('dashboard.field.delete', $field->id) }}" method="POST"
                                                data-use-submit-alert="Apakah anda yakin ingin menghapus lapangan {{ $field->name }}? Data yang dihapus tidak dapat dikembalikan.">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('root')
    <x-field-modal />
    <x-field-schedule-modal />
@endpush
