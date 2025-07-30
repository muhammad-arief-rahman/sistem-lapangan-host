@extends('layout.auth')

@section('content')
    <h1 class="mt-5 text-[32px] font-semibold text-center">Selamat Datang</h1>

    <div class="mt-5">
        <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div class="form-control">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    class="input input-main" required
                    placeholder="Masukkan email">

                @error('email')
                    <span class="text-red-500 text-sm ">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                    class="input input-main" required
                    placeholder="Masukkan password">

                @error('password')
                    <span class="text-red-500 text-sm ">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">Masuk</button>

            <div class="mt-5 text-center">
                <span class="text-zinc-500">Belum punya akun? </span>
                <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar</a>
            </div>
        </form>
    </div>
@endsection
