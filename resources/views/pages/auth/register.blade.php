@extends('layout.auth')

@section('content')
    <h1 class="mt-5 text-[32px] font-semibold text-center">Daftar</h1>

    <div class="mt-5">
        <form action="{{ route('register.post') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div class="form-control">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="input input-main"
                    required placeholder="Masukkan nama anda" value="{{ old('name') }}">

                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    class="input input-main" required placeholder="Masukkan email anda"
                    value="{{ old('email') }}">

                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="phone" class="form-label">No. HP</label>
                <input type="text" name="phone" id="phone"
                    class="input input-main" required
                    placeholder="Masukkan nomor handphone anda" value="{{ old('phone') }}">

                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                    class="input input-main" required
                    placeholder="Masukkan password anda">

                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <div class="flex gap-2">
                    <input type="password" name="confirm_password" id="confirm_password"
                        class="input input-main grow" required
                        placeholder="Konfirmasi password anda">

                    <select name="role" id="role" class="border border-zinc-200 rounded-lg px-2 h-10 md:h-12 text-sm"
                        required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                        <option value="community" {{ old('role') == 'community' ? 'selected' : '' }}>Komunitas</option>
                        <option value="field_manager" {{ old('role') == 'community' ? 'selected' : '' }}>Pengelola Lapangan
                        </option>
                        <option value="photographer" {{ old('role') == 'photographer' ? 'selected' : '' }}>Fotografer
                        </option>
                        <option value="referee" {{ old('role') == 'referee' ? 'selected' : '' }}>Wasit</option>
                    </select>
                </div>

                @error('confirm_password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="terms" id="terms"
                    class="w-5 h-5 border border-zinc-200 rounded-lg accent-primary" required>
                <label for="terms" class="form-label">Saya setuju dengan <a
                        href="{{ asset('assets/others/syarat-dan-ketentuan.pdf') }}" target="_blank"
                        class="text-primary font-semibold hover:underline">syarat dan ketentuan</a></label>

                @error('terms')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-primary text-white py-2 shadow-xl shadow-primary/20 rounded-lg cursor-pointer duration-100 mt-2 h-10 md:h-12">Daftar</button>

            <div class="mt-5 text-center">
                <span class="text-zinc-500">Sudah punya akun? </span>
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk</a>
            </div>
        </form>
    </div>
@endsection
