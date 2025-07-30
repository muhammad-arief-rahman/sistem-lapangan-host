@extends('layout.dashboard')

@section('title', 'Pengguna')

@section('content')
    <div class="dashboard-container">
        <x-breadcrumbs :breadcrumbs="['Pengguna' => route('dashboard.user.index')]" />
        <div class="dashboard-cards">
            <x-dashboard-card title="Jumlah Pengguna" value="{{ $cardData->totalUsers }}">
                <x-slot:icon>
                    <i class="fa-solid fa-users"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Wasit" value="{{ $cardData->totalReferees }}">
                <x-slot:icon>
                    <i class="fa-solid fa-person-walking"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Fotografer" value="{{ $cardData->totalPhotographers }}">
                <x-slot:icon>
                    <i class="fa-solid fa-camera"></i>
                </x-slot:icon>
            </x-dashboard-card>
            <x-dashboard-card title="Pengelola Lapangan" value="{{ $cardData->totalFieldManagers }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-land-plot-icon lucide-land-plot">
                        <path d="m12 8 6-3-6-3v10" />
                        <path
                            d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12" />
                        <path d="m6.49 12.85 11.02 6.3" />
                        <path d="M17.51 12.85 6.5 19.15" />
                    </svg>
                </x-slot:icon>
            </x-dashboard-card>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium">Daftar Pengguna</h2>
                <div>
                        @if (authorized('super_admin'))
                            <button data-modal="add-user"
                                class="bg-primary text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                                Tambah Pengguna
                            </button>
                        @endif
                </div>
            </div>
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
            @endif
            <div class="shadow-main bg-white rounded-lg p-4 md:p-6">
                <table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Bergabung Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="[&>td]:whitespace-nowrap">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->getRoleName() }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="flex gap-2">
                                    @if (authorized('super_admin'))
                                        <button data-modal="edit-user-{{ $user->id }}"
                                            class="btn btn-primary btn-sm">
                                            Edit
                                        </button>
                                    @endif
                                    <form
                                    data-use-submit-alert="Yakin ingin menghapus pengguna ini? data yang dihapus tidak dapat dikembalikan."
                                    action="{{ route('dashboard.user.delete', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
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
    {{-- Modal for adding a new user --}}
    <x-user-modal id="add-user" :roles="$roles" />

    {{-- Modals for editing existing users --}}
    @foreach ($users as $user)
        <x-user-modal id="edit-user-{{ $user->id }}" :user="$user" :roles="$roles" />
    @endforeach

@endpush
