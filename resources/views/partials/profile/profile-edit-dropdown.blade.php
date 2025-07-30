<div {{-- data-use-toggle='edit-profile'  --}} {{-- style="visibility: hidden;" --}}
    class="grid overflow-hidden drop-shadow-main relative z-10 transition-all duration-500 data-[is-open='true']:grid-rows-[1fr] data-[is-open='false']:grid-rows-[0fr]">
    <div class="overflow-hidden">
        <div class="bg-white p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Edit Profil</h3>
            <form action="{{ route('dashboard.profile.update-profile') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-4">
                @csrf
                <div class="form-control">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="input input-main" required
                        placeholder="Masukkan nama anda" value="{{ old('name', $user->name) }}">

                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="input input-main" required
                        placeholder="Masukkan email anda" value="{{ old('email', $user->email) }}">

                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="phone" class="form-label">No. HP</label>
                    <input type="text" name="phone" id="phone" class="input input-main" required
                        placeholder="Masukkan nomor handphone anda" value="{{ old('phone', $user->phone) }}">

                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="password" class="form-label">Password (Opsional)</label>
                    <input type="password" name="password" id="password" class="input input-main"
                        placeholder="Masukkan password anda">

                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="confirm_password" class="form-label">Konfirmasi Password (Opsional)</label>
                    <div class="flex gap-2">
                        <input type="password" name="confirm_password" id="confirm_password" class="input input-main"
                            placeholder="Konfirmasi password anda">
                    </div>

                    @error('confirm_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <img src="{{ $user->getImageUrl() }}" alt="Profile"
                        class="size-24 rounded-full mb-2 bg-zinc-200 object-cover" id="profile-preview">
                    <label for="photo" class="form-label">Ganti Foto Profil</label>
                    <input type="file" name="photo" id="photo" class="input input-main file:h-full"
                        accept="image/*">
                    @error('photo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            window.useToggle('edit-profile', (isOpen) => {
                $('#edit-profile-text').text(isOpen ? 'Batal' : 'Edit Profil');
            })

            $('#photo').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        })
    </script>
@endpush
