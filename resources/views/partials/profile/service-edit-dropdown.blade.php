<div {{-- data-use-toggle='edit-service' style="visibility: hidden;" --}}
    class="grid overflow-hidden drop-shadow-main relative z-10 transition-all duration-500 data-[is-open='true']:grid-rows-[1fr] data-[is-open='false']:grid-rows-[0fr]">
    <div class="overflow-hidden">
        <div class="bg-white p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4">Edit Layanan</h3>

            <form action="{{ route('dashboard.profile.update-service') }}" method="POST" class="flex flex-col gap-4"
                enctype="multipart/form-data">
                @csrf
                <div class="form-control">
                    <label for="price_per_hour" class="form-label">Biaya Tiap Jam</label>
                    <p class="text-sm text-zinc-700">
                        Masukkan harga yang anda tawarkan untuk tiap jam. <br>Misal: Rp 100.000
                    </p>
                    <input type="number" name="price_per_hour" id="price_per_hour"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required placeholder="Masukkan harga"
                        value="{{ old('price_per_hour', $service->price_per_hour) }}">

                    @error('price_per_hour')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="w-full border border-zinc-200 rounded-lg px-4 min-h-32" required
                        placeholder="Masukkan deskripsi">{{ old('description', $service->description) }}</textarea>

                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control">
                    <label for="portfolio" class="form-label">Link Portofolio</label>
                    <input type="text" name="portfolio" id="portfolio" class="input input-main"
                        value="{{ old('portfolio', $service->portfolio) }}">

                    <p class="text-sm text-zinc-700">
                        Silahkan masukkan link portofolio anda. <br>
                        Portofolio dapat berupa link ke media sosial, website, atau platform lain yang menampilkan karya anda.
                    </p>

                    @error('portofolio')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-2 shadow-xl shadow-primary/20 rounded-lg cursor-pointer duration-100 mt-2 h-12">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            window.useToggle('edit-service', (isOpen) => {
                $('#edit-service-text').text(isOpen ? 'Batal' : 'Edit Layanan');
            })

            $('#portfolio').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#portfolio-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        })
    </script>
@endpush
