@extends('layout.auth')

@section('content')
    <h1 class="mt-5 text-[32px] font-semibold text-center">Lengkapi Profil</h1>

    <p class="text-zinc-700 text-center mt-2">
        Silahkan lengkapi profil untuk jasa yang anda tawarkan.
    </p>

    <div class="mt-5">
        <form action="{{ route('complete-profile.post') }}" method="POST" class="flex flex-col gap-4"
            enctype="multipart/form-data">
            @csrf
            <div class="form-control">
                <label for="price_per_hour" class="form-label">Biaya Tiap Jam</label>
                <p class="text-sm text-zinc-700">
                    Masukkan harga yang anda tawarkan untuk tiap jam.
                </p>
                <input type="number" name="price_per_hour" id="price_per_hour"
                    class="w-full border border-zinc-200 rounded-lg px-4 h-12" required placeholder="Rp 100.000">

                @error('price_per_hour')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="description" class="form-label">Deskripsi</label>

                <p class="text-sm text-zinc-700">
                    Berikan deskripsi singkat tentang jasa yang anda tawarkan. <br>
                    Deskripsi ini akan membantu calon pelanggan memahami layanan anda.
                </p>

                <textarea name="description" id="description" class="w-full border border-zinc-200 rounded-lg px-4 min-h-32" required
                    placeholder="Saya adalah seorang fotografer yang..."></textarea>



                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="portofolio" class="form-label">Portofolio</label>
                <p class="text-sm text-zinc-700">
                    Tambahkan link portofolio anda. <br>
                    Portofolio akan ditampilkan pada halaman jasa anda.
                </p>

                <input type="text" name="portofolio" id="portofolio" class="input input-main" required
                    placeholder="https://link-portofolio-anda.com">



                @error('portofolio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-primary text-white py-2 shadow-xl shadow-primary/20 rounded-lg cursor-pointer duration-100 mt-2 h-12">
                Lengkapi Profil
            </button>
        </form>
    </div>
@endsection
