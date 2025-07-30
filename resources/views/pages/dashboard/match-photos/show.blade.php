@extends('layout.dashboard')

@section('title', 'Foto Pertandingan')

@section('content')
    <div class="dashboard-container">

        <x-breadcrumbs :breadcrumbs="[
            'Foto Pertandingan' => route('dashboard.match-photos.index'),
            'Pertandingan #' . $booking->id => route('dashboard.match-photos.show', $booking->id),
        ]" />

        <h2 class="text-lg font-medium">Foto Pertandingan #{{ $booking->id }}</h2>

        <p class="text-gray-500">
            Silahkan unggah foto-foto pertandingan yang telah Anda ambil disini. Anda juga dapat menambahkan link tambahan
            apabila anda memiliki link ke album foto di platform lain.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse ($booking->photos as $photo)
                <div class=" bg-white rounded-2xl p-4 shadow-main">
                    <div class="aspect-video overflow-hidden rounded-lg relative group">
                        <a href="{{ $photo->path }}" target="_blank" class="contents">
                            <img src="{{ $photo->path }}" alt="{{ $photo->name }}" class="w-full h-full object-cover">
                        </a>

                        @if (auth()->user()->role === 'photographer')
                            <div
                                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-white rounded-full shadow-lg">
                                <form data-use-submit-alert="Yakin ingin menghapus foto ini?"
                                    action="{{ route('dashboard.match-photos.delete', $photo->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <p class="text-gray-500 mt-4 text-sm">Dibuat pada:
                        {{ $photo->created_at->translatedFormat('l, d F Y, H:i') }}</p>
                </div>
            @empty
                <div class="col-span-1 md:col-span-3 bg-white rounded-lg p-4 shadow-main min-h-32 grid place-items-center">
                    <p class="text-center text-gray-500">Tidak ada foto untuk pertandingan ini.</p>
                </div>
            @endforelse

        </div>

        @if (auth()->user()->role === 'photographer')
            <form action="{{ route('dashboard.match-photos.store', $booking->id) }}" class="flex flex-col gap-4"
                enctype="multipart/form-data" method="post">
                @csrf

                <div class="flex items-center gap-4 justify-between">
                    <div class="flex items-center gap-4">
                        <label for="photo-input" class="btn btn-primary">
                            <i class="fa-solid fa-pencil"></i>
                            <span>Atur Foto</span>

                            <input type="file" class="hidden" accept="image/*" name="photos[]" id="photo-input" multiple>
                        </label>

                        <div class="flex items-center gap-2">
                            <button type="button" class="btn btn-primary" data-modal="add-match-photo-link">
                                <i class="fa-solid fa-link"></i>
                                <span>Atur Link</span>
                            </button>

                            <div class="text-sm text-gray-500">
                                <span class="font-semibold">Link Tambahan:</span>
                                <a class="text-blue-500 hover:underline" href="{{ $booking->match_photo_link ?? '#' }}">
                                    {{ $booking->match_photo_link ?? 'Tidak ada' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary hidden" id="upload-button">
                        <i class="fa-solid fa-upload"></i>
                        <span>Simpan Foto</span>
                    </button>
                </div>

                @error('photos')
                    <div class="text-red-500 text-sm ">
                        {{ $message }}
                    </div>
                @enderror

                <div id="preview-area" class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
                </div>

            </form>
        @endif

    </div>
@endsection

@if (auth()->user()->role === 'photographer')
    @push('root')
        <x-modal id="add-match-photo-link">
            <form action="{{ route('dashboard.match-photos.update-link', $booking->id) }}" method="post"
                class="flex flex-col gap-4" enctype="multipart/form-data">
                @csrf

                <h2 class="text-lg font-semibold text-primary">Atur Link Foto</h2>

                <div class="form-control">
                    <label for="match_photo_link" class="form-label">Link Tambahan</label>
                    <input type="url" name="match_photo_link" id="match_photo_link" class="input input-main w-full"
                        placeholder="Masukkan link tambahan"
                        value="{{ old('match_photo_link', $booking->match_photo_link) }}">
                    @error('match_photo_link')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i>
                        <span>Simpan Link</span>
                    </button>
                </div>
            </form>
        </x-modal>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const photoInput = document.querySelector('#photo-input');
                const previewArea = document.querySelector('#preview-area');
                const uploadButton = document.querySelector('#upload-button');

                photoInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    previewArea.innerHTML = ''; // Clear previous previews

                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('w-full', 'h-auto', 'rounded-lg', 'shadow-main');

                            const container = document.createElement('div');
                            container.classList.add('bg-white', 'rounded-2xl', 'p-4',
                                'shadow-main');
                            container.appendChild(img);
                            previewArea.appendChild(container);
                        };
                        reader.readAsDataURL(file);
                    });

                    if (files.length > 0) {
                        uploadButton.classList.remove('hidden');
                    } else {
                        uploadButton.classList.add('hidden');
                    }
                });
            })
        </script>
    @endpush
@endif
