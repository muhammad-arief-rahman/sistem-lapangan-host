@php
    $id = $field ? "edit-field-$field->id" : 'add-field';
    $route = $field ? route('dashboard.field.update', $field->id) : route('dashboard.field.store');
@endphp

<x-modal id="{{ $id }}">
    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">
        @csrf
        <h4 class="font-semibold text-primary text-xl">
            {{ $field ? 'Edit Lapangan' : 'Tambah Lapangan' }}
        </h4>
        <div class="grid md:grid-cols-2 gap-12 mt-6">
            <div class="flex flex-col gap-4">
                <div class="form-control">
                    <label for="name" class="form-label">Nama Lapangan</label>
                    <input type="text" id="name" name="name" class="input input-main"
                        placeholder="Masukkan nama lapangan" required value="{{ $field ? $field->name : old('name') }}"
                        required>

                    @error('name')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex gap-4 items-center">
                        <div class="form-control flex-1">
                            <label for="district-{{ $id }}" class="form-label">Kecamatan</label>
                            <select id="district-{{ $id }}" name="district_id"
                                class="input input-main flex-1">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ $field && $field->village && $field->village->district_id == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control flex-1">
                            <label for="village-{{ $id }}" class="form-label">Kelurahan</label>
                            <select id="village-{{ $id }}" name="village_id" class="input input-main flex-1"
                                {{ $field ? '' : 'disabled' }}>
                                <option value="">Pilih Kecamatan Dulu</option>
                            </select>
                        </div>
                    </div>
                    @error('village_id')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="location" class="form-label">Alamat</label>
                    <input type="text" id="location" type="text" name="location" class="input input-main "
                        placeholder="Masukkan lokasi lapangan" required
                        value="{{ $field ? $field->location : old('location') }}" required>

                    @error('location')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea type="text" id="description" name="description" class="input input-main min-h-32"
                        placeholder="Masukkan nomor deskripsi lapangan" required>{{ $field ? $field->description : old('description') }}</textarea>

                    @error('description')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
                @if (authorized('super_admin'))
                    <div class="form-control">
                        <label for="description" class="form-label">Pengelola Lapangan</label>
                        <select name="manager_id" id="manager_id" class="input input-main">
                            <option value="">Pilih Pengelola</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}"
                                    {{ $field && $field->manager_id == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('description')
                            <span class="text-red-500 text-sm ">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-4">
                <div class="form-control">
                    <label for="facilities" class="form-label">Fasilitas</label>
                    {{-- Checkboxes --}}
                    <div class="grid grid-cols-2 gap-4 max-h-32 overflow-y-auto">
                        @forelse ($facilities as $facility)
                            <label class="cursor-pointer flex items-center gap-2">
                                <input type="checkbox" name="facilities[]"
                                    class="rounded border-zinc-300 bg-zinc-50 accent-primary text-primary outline-primary"
                                    value="{{ $facility->id }}"
                                    {{ $field && $field->facilities->contains($facility) ? 'checked' : '' }}>
                                {{ $facility->name }}
                            </label>
                        @empty
                            <span class="text-gray-500">Tidak ada fasilitas tersedia</span>
                        @endforelse
                    </div>
                </div>
                <div class="form-control">
                    <div class="form-label">Upload Foto Lapangan</div>
                    <div class="bg-zinc-100 aspect-video relative border border-zinc-300 rounded-md">
                        <img src="#" alt="image-preview" id="image-preview-{{ $id }}"
                            class="w-full h-full object-cover rounded-md hidden">
                        <label tabindex="0" for="image-{{ $id }}"
                            class="cursor-pointer absolute inset-0 hover:backdrop-blur-md rounded-md hover:backdrop:brightness-75 duration-200 grid place-items-center">
                            <div class="flex flex-col items-center gap-2 w-full data-[is-active='true']:text-white data-[is-active='true']:opacity-0 data-[is-active='true']:hover:opacity-100"
                                id="image-label-{{ $id }}">
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M25 31.25H29.1667V18.75H34.375L25 9.375M25 31.25H20.8333V18.75H15.625L25 9.375"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12.5 39.5835H37.5" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="text-sm font-medium  max-w-2/3 text-pretty text-center"
                                    id="image-name-{{ $id }}">
                                    Upload Foto Lapangan
                                </div>
                            </div>
                        </label>
                    </div>

                    <input type="file" id="image-{{ $id }}" name="image" accept="image/*"
                        class="hidden">
                    @error('image')
                        <span class="text-red-500 text-sm ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="price_per_hour" class="form-label">Harga Lapangan per Jam</label>
                    <input type="number" id="price_per_hour" name="price_per_hour" class="input input-main"
                        placeholder="Masukkan harga lapangan" required
                        value="{{ $field ? $field->price_per_hour : old('price_per_hour') }}">

                    @error('price_per_hour')
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
            const modal = document.querySelector('#{{ $id }}');

            const imageInput = modal.querySelector('#image-{{ $id }}');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];

                document.querySelector('#image-name-{{ $id }}').innerText = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.querySelector(
                        '#image-preview-{{ $id }}');
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);

                document.querySelector('#image-label-{{ $id }}').dataset.isActive =
                    'true';

            })

            const districts = @json($districts);

            const districtMap = districts.reduce((map, districts) => {
                map[districts.id] = districts.villages
                return map;
            }, {});

            const initialVillageSelect = @json($field ? $field->village_id : null);

            function updateVillage() {
                const districtSelect = modal.querySelector('#district-{{ $id }}');
                const villageSelect = modal.querySelector('#village-{{ $id }}');
                villageSelect.innerHTML = '';

                const selectedDistrictId = districtSelect.value;

                // Always add a default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Pilih Kelurahan';
                villageSelect.appendChild(defaultOption);


                if (selectedDistrictId) {
                    const villages = districtMap[selectedDistrictId] || [];
                    villages.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        villageSelect.appendChild(option);
                    });
                    villageSelect.disabled = false;
                } else {
                    villageSelect.disabled = true;
                }

                // Set initial value if available
                if (initialVillageSelect) {
                    villageSelect.value = initialVillageSelect;
                }
            }

            document.querySelector('#district-{{ $id }}').addEventListener('change', function() {
                updateVillage();
            });

            updateVillage();
        })
    })()
</script>
