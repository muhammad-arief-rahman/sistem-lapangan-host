@extends('layout.landing')

@section('title', 'Trofeo')

@section('content')
    <div class="w-content mx-auto md:py-8 py-4 flex flex-col md:gap-6 gap-4">
        <div class="flex gap-4">
            <form action="{{ route('event.trofeos.index') }}" class="flex items-center gap-4 grow w-full justify-between flex-wrap">
                <div class="flex items-center gap-2 flex-wrap grow md:max-w-sm flex-1 min-w-xs">
                    <input type="text" placeholder="Cari lapangan"
                        class="flex-1 min-w-0 bg-white h-12 border field-sizing-content  border-zinc-200 rounded-full px-8 "
                        name="search" value="{{ request('search') }}">

                    <button type="submit"
                        class="bg-primary text-white h-12 aspect-square cursor-pointer hover:brightness-90 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                    @if (request('search'))
                        <a href="{{ route('event.trofeos.index') }}" title="Reset Pencarian"
                            class="bg-primary text-white h-12 aspect-square cursor-pointer hover:brightness-90 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2 md:max-w-sm flex-1 min-w-xs">
                    <select name="district" class="bg-white border border-zinc-200 rounded-full px-4 h-12 {{ request('district') ? 'col-span-1' : 'col-span-2' }}">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}"
                                {{ request('district') == $district->id ? 'selected' : '' }}>
                                {{ ucwords(strtolower($district->name)) }}
                            </option>
                        @endforeach
                    </select>

                    @if ($villages->isNotEmpty())
                        <select name="village" class="bg-white border border-zinc-200 rounded-full px-4 h-12 col-span-1">
                            <option value="">Semua Kelurahan</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}"
                                    {{ request('village') == $village->id ? 'selected' : '' }}>
                                    {{ ucwords(strtolower($village->name)) }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid lg:grid-cols-3 md:grid-cols-2 md:gap-8 gap-4">
            @forelse ($trofeos as $trofeo)
                <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col gap-3">
                    <div class="aspect-video">
                        <img src="{{ $trofeo->booking->field->getImageUrlAttribute() }}" alt="trofeo"
                            class="aspect-video w-full rounded-md object-cover bg-zinc-200">
                    </div>
                    <h3 class="font-semibold text-[20px]">{{ $trofeo->match_name }}</h3>
                    <div class="text-sm text-zinc-700">
                        {{ $trofeo->description }}
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="size-4">
                            <x-icons.plot />
                        </div>
                        <span class="text-sm">
                            {{ $trofeo->booking->field->name }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="size-4">
                            <x-icons.map-pin />
                        </div>
                        <span class="text-sm">
                            {{ $trofeo->booking->field->location }}
                        </span>
                    </div>

                    <div class="text-lg text-primary font-semibold">
                        {{ format_rp($trofeo->registration_fee) }}
                    </div>

                    <a href="{{ route('event.trofeos.show', $trofeo->id) }}" class="btn btn-primary">
                        Lihat Trofeo
                    </a>
                </div>
            @empty
                @if (request('search'))
                    <div class="col-span-3 text-center bg-white p-8 rounded-lg shadow-xl flex flex-col items-center gap-4">
                        <div class="size-12 bg-zinc-50 border border-zinc-200 rounded-full grid place-items-center">
                            <i class="fa-solid fa-circle-question text-lg text-primary"></i>
                        </div>
                        <h2 class="text-xl font-semibold">
                            Trofeo tidak ditemukan
                        </h2>
                        <p class="text-zinc-500 text-sm">
                            Coba ubah kata kunci pencarian atau <a href="{{ route('home') }}" class="text-primary">hapus
                                pencarian</a>.
                        </p>
                    </div>
                @else
                    <div class="col-span-3 text-center bg-white p-8 rounded-lg shadow-xl flex flex-col items-center gap-4">
                        <div class="size-12 bg-zinc-50 border border-zinc-200 rounded-full grid place-items-center">
                            <i class="fa-solid fa-circle-question text-lg text-primary"></i>
                        </div>
                        <h2 class="text-xl font-semibold">Trofeo tidak tersedia</h2>
                        <p class="text-zinc-500 text-sm">
                            Saat ini tidak ada trofeo yang tersedia. Silakan cek kembali nanti atau <a href="{{ route('home') }}" class="text-primary hover:underline">buat trofeo baru</a>.
                        </p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('[name="district"]').addEventListener('change', function(e) {
                const districtSelect = e.target;
                const url = new URL(window.location.href);

                const villageSelect = document.querySelector('[name="village"]');
                url.searchParams.delete('village'); // Reset village selection when district changes
                if (villageSelect) {
                    villageSelect.value = ''; // Reset village selection when district changes

                }

                if (!districtSelect.value) {
                    url.searchParams.delete('district');
                } else {
                    url.searchParams.set('district', districtSelect.value);
                }

                window.location.href = url.toString();
            });

            const villageSelect = document.querySelector('[name="village"]');

            if (villageSelect) {
                villageSelect.addEventListener('change', function(e) {
                    const villageSelect = e.target;
                    const url = new URL(window.location.href);

                    if (!villageSelect.value) {
                        url.searchParams.delete('village');
                    } else {
                        url.searchParams.set('village', villageSelect.value);
                    }

                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endpush
