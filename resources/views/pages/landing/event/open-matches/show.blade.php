@extends('layout.landing')

@section('title', 'Detail Open Match')

@section('content')
    <div class="w-content mx-auto md:py-8 py-4 flex flex-col md:gap-6 gap-4">
        <div class="bg-white lg:p-16 md:p-8 p-6 rounded-lg shadow-xl grid md:grid-cols-2 lg:gap-16 md:gap-8 gap-4">
            <div class="flex flex-col gap-6">
                <img src="{{ $openMatch->booking->field->getImageUrlAttribute() }}"
                    alt="lapangan-{{ $openMatch->booking->field->name }}"
                    class="rounded-md object-cover aspect-video w-full bg-zinc-200">
                <div>
                    <h2 class="text-[20px] font-semibold text-primary">
                        {{ $openMatch->match_name }}
                    </h2>
                </div>
                <p>{{ $openMatch->description ?? 'Deskripsi tidak tersedia' }}</p>
            </div>
            <div>
                <div class="md:p-6 p-4 rounded-lg border border-zinc-200 flex flex-col gap-4">
                    <p class="text-sm font-semibold">Biaya Registrasi</p>
                    <div class="font-semibold text-[20px]">
                        {{ format_rp($openMatch->registration_fee) }}
                        <span class="text-sm font-normal">/ Tim</span>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-4">
                    <div class="flex items-center gap-1 ">
                        <div class="size-4">
                            <x-icons.plot />
                        </div>
                        <span class="text-sm font-semibold">
                            {{ $openMatch->booking->field->name }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1 ">
                        <div class="size-4">
                            <x-icons.map-pin />
                        </div>
                        <span class="text-sm font-semibold">
                            {{ $openMatch->booking->field->location }}
                        </span>
                    </div>
                </div>
                <form data-use-submit-alert="Daftar open match ini? Anda akan diarahkan untuk melakukan pembayaran."
                    action="{{ route('event.open-matches.register', $openMatch->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-8 w-full">
                        Daftar Open Match
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
