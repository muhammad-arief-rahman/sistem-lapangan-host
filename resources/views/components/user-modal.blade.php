@props(['user' => null, 'id', 'roles'])

@php
    $route = $user ? route('dashboard.user.update', $user->id) : route('dashboard.user.store');
    $method = $user ? 'PUT' : 'POST';
@endphp

<x-modal id="{{ $id }}">
    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method($method)
        
        <h4 class="font-semibold text-primary text-xl">
            {{ $user ? 'Edit Pengguna' : 'Tambah Pengguna' }}
        </h4>
        <div class="grid md:grid-cols-2 gap-12 mt-6">
            <div class="flex flex-col gap-4">
                <div class="form-control">
                    <label for="name-{{ $id }}" class="form-label">Nama</label>
                    <input type="text" id="name-{{ $id }}" name="name" class="input input-main"
                        placeholder="Masukkan nama pengguna" required value="{{ $user ? $user->name : old('name') }}">
                    @error('name')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="email-{{ $id }}" class="form-label">Email</label>
                    <input type="email" id="email-{{ $id }}" name="email" class="input input-main"
                        placeholder="Masukkan email pengguna" required value="{{ $user ? $user->email : old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="phone-{{ $id }}" class="form-label">Nomor HP</label>
                    <input type="text" id="phone-{{ $id }}" name="phone" class="input input-main"
                        placeholder="Masukkan nomor HP pengguna" value="{{ $user ? $user->phone : old('phone') }}">
                    @error('phone')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="role-{{ $id }}" class="form-label">Role</label>
                    <select name="role" id="role-{{ $id }}" class="input input-main" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $roleValue => $roleName)
                            <option value="{{ $roleValue }}"
                                {{ ($user && $user->role == $roleValue) || old('role') == $roleValue ? 'selected' : '' }}>
                                {{ $roleName }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="form-control">
                    <label for="password-{{ $id }}" class="form-label">Password</label>
                    <input type="password" id="password-{{ $id }}" name="password" class="input input-main"
                        placeholder="{{ $user ? 'Biarkan kosong jika tidak ingin mengubah' : 'Masukkan password' }}"
                        {{ $user ? '' : 'required' }}>
                    @error('password')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="password_confirmation-{{ $id }}" class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation-{{ $id }}" name="password_confirmation"
                        class="input input-main"
                        placeholder="{{ $user ? 'Biarkan kosong jika tidak ingin mengubah' : 'Konfirmasi password' }}"
                        {{ $user ? '' : 'required' }}>
                </div>

                <div class="form-control">
                    <div class="form-label">Upload Foto Profil</div>
                    <div class="bg-zinc-100 aspect-video relative border border-zinc-300 rounded-md">
                        <img src="{{ $user ? $user->getImageUrl() : '#' }}" alt="image-preview"
                            id="image-preview-{{ $id }}"
                            class="w-full h-full object-cover rounded-md {{ $user && $user->photo ? '' : 'hidden' }}">
                        <label tabindex="0" for="photo-{{ $id }}"
                            class="cursor-pointer absolute inset-0 hover:backdrop-blur-md rounded-md hover:backdrop:brightness-75 duration-200 grid place-items-center">
                            <div class="flex flex-col items-center gap-2 w-full data-[is-active='true']:text-white data-[is-active='true']:opacity-0 data-[is-active='true']:hover:opacity-100"
                                id="image-label-{{ $id }}"
                                data-is-active="{{ $user && $user->photo ? 'true' : 'false' }}">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M25 31.25H29.1667V18.75H34.375L25 9.375M25 31.25H20.8333V18.75H15.625L25 9.375"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12.5 39.5835H37.5" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="text-sm font-medium max-w-2/3 text-pretty text-center"
                                    id="image-name-{{ $id }}">
                                    {{ $user && $user->photo ? $user->photo : 'Upload Foto Profil' }}
                                </div>
                            </div>
                        </label>
                    </div>

                    <input type="file" id="photo-{{ $id }}" name="photo" accept="image/*"
                        class="hidden">
                    @error('photo')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit"
                class="bg-primary text-white drop-shadow-main px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                Simpan
            </button>
        </div>
    </form>
</x-modal>

<script>
    (() => {
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.querySelector('#{{ $id }}');

            const imageInput = modalElement.querySelector('#photo-{{ $id }}');
            const imagePreview = modalElement.querySelector('#image-preview-{{ $id }}');
            const imageNameDisplay = modalElement.querySelector('#image-name-{{ $id }}');
            const imageLabel = modalElement.querySelector('#image-label-{{ $id }}');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    imageNameDisplay.innerText = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                    imageLabel.dataset.isActive = 'true';
                } else {
                    // If no file selected (e.g., user cancels file dialog)
                    imageNameDisplay.innerText = 'Upload Foto Profil';
                    imagePreview.classList.add('hidden');
                    imagePreview.src = '#'; // Clear preview
                    imageLabel.dataset.isActive = 'false';
                }
            });

            @if (old('photo') && !$user)
            @elseif ($user && $user->photo)
                imagePreview.src = "{{ $user->getImageUrl() }}";
                imagePreview.classList.remove('hidden');
                imageNameDisplay.innerText = "{{ $user->photo }}";
                imageLabel.dataset.isActive = 'true';
            @endif

            @if ($errors->any() && (old('id') == $id || (!$user && $errors->hasAny(['name', 'email', 'phone', 'role', 'password', 'photo']))))
                modalElement.classList.add('is-open');
            @endif
        });
    })();
</script>
