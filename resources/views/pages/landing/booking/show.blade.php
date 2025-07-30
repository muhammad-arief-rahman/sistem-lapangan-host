@extends('layout.landing')

@section('content')
    <div class="w-content mx-auto md:py-8 py-4 flex flex-col md:gap-6 gap-4">
        <div class="bg-white lg:p-16 md:p-8 p-6 rounded-lg shadow-xl grid md:grid-cols-2 lg:gap-16 md:gap-8 gap-4">
            <div class="flex flex-col gap-6">
                <img src="{{ $field->getImageUrlAttribute() }}" alt="lapangan-{{ $field->name }}"
                    class="rounded-md object-cover aspect-video w-full bg-zinc-200">
                <div>
                    <h2 class="text-[20px] font-semibold text-primary">
                        {{ $field->name }}
                    </h2>
                    <div></div>
                </div>
                <p>{{ $field->description ?? 'Deskripsi tidak tersedia' }}</p>
            </div>
            <div>
                <div class="md:p-8 p-6 rounded-lg border border-zinc-200">
                    <div class="text-center font-semibold text-[20px]">
                        {{ format_rp($field->price_per_hour) }}
                        <span class="text-sm font-normal">/ Jam</span>
                    </div>
                    <button data-modal="schedule-modal"
                        class="bg-primary w-full mt-6 text-white grid place-items-center rounded-lg font-semibold cursor-pointer duration-100 h-10 md:h-12">
                        Cek Jadwal
                    </button>
                </div>
                <div class="flex items-center gap-4 mt-8 md:mt-6">
                    <div class="size-10 grid place-items-center bg-primary rounded-full shrink-0 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin size-5">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">
                        {{ $field->fullLocation }}
                    </span>
                </div>
                <div class="flex gap-2 mt-4 grow">
                    <div class="flex flex-col gap-2 grow">
                        <h4 class="text-sm font-semibold flex items-center">
                            Fasilitas
                        </h4>
                        <div class="grid md:grid-cols-2 md:gap-2 gap-1">
                            @if ($field->facilities->count())
                                @foreach ($field->facilities as $facility)
                                    <div class="text-sm font-medium flex items-center gap-2">
                                        <div class="size-6 grid place-items-center text-primary ">
                                            {!! $facility->icon ?? '<i class="fa-solid fa-check"></i>' !!}
                                        </div>
                                        <span>{{ $facility->name }}</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-sm text-zinc-500">Tidak ada fasilitas yang tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('root')
    <x-modal id="schedule-modal">
        <form action="{{ route('booking.store', $field->id) }}" method="POST"
            data-use-submit-alert="Apakah anda yakin ingin melakukan pesanan ini? anda akan diarahkan ke halaman pembayaran.">
            @csrf
            <div data-form-index="0">
                <div class="relative">
                    <div class="absolute inset-0 grid place-items-center text-2xl font-medium" id="loading">
                        Loading...
                    </div>

                    <h4 class="text-lg font-semibold text-primary">Jadwal Lapangan</h4>

                    <div id="calendar" class="h-full bg-white mt-4 clickable-calendar"></div>
                </div>

                <h4 class="text-lg font-semibold text-primary mt-4">Pesan Lapangan</h4>
                <p class="text-sm text-zinc-500">Silahkan pilih tanggal dan waktu yang diinginkan</p>

                <div class="grid md:grid-cols-4  gap-4 mt-4">
                    <div class="form-control">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" name="date" id="date"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required value="{{ old('date', date('Y-m-d', strtotime('+1 day'))) }}">
                    </div>
                    <div class="form-control">
                        <label for="time" class="form-label">Waktu</label>
                        <input type="time" name="time" id="time"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required step="3600" value="{{ old('time', date('H:00', strtotime('+1 hour'))) }}">
                    </div>
                    <div class="form-control">
                        <label for="duration" class="form-label">Durasi (Jam)</label>
                        <input type="number" name="duration" id="duration"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required step="1" min="1" max="24" placeholder="Masukkan durasi"
                            value="{{ old('duration', 1) }}">
                        @error('duration')
                            <span class="text-red-500 text-sm ">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label for="type" class="form-label">Tipe Pesanan</label>
                        <select name="type" id="type"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required>
                            {{-- <option value="" disabled selected>Pilih Tipe</option> --}}
                            <option {{ old('type') === 'regular' ? 'selected' : '' }} value="regular">
                                Booking Lapangan
                            </option>
                            <option {{ old('type') === 'open_match' ? 'selected' : '' }} value="open_match">
                                Open Match
                            </option>
                            <option {{ old('type') === 'trofeo' ? 'selected' : '' }} value="trofeo">
                                Trofeo
                            </option>
                        </select>

                        @error('type')
                            <span class="text-red-500 text-sm ">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-4" id="event-fields">
                    <div class="form-control">
                        <label for="date" class="form-label">Judul </label>
                        <input type="text" name="title" id="title" class="input input-main"
                            value="{{ old('title') }}" placeholder="Masukkan judul kegiatan">
                    </div>
                    <div class="form-control">
                        <label for="date" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="input input-main min-h-32"
                            placeholder="Berikan deskripsi kegiatan yang menarik">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" data-form-next class="btn btn-primary">
                        Selanjutnya
                    </button>
                </div>
            </div>

            <div data-form-index="1" class="hidden">
                <h4 class="text-lg font-semibold text-primary">Pilih Jasa (Opsional)</h4>
                <p class="text-sm text-zinc-500">Silahkan pilih jasa yang diinginkan</p>

                <div id="service-list" class="mt-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                        <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                        <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                        <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4 mt-4">
                    {{-- <div class="form-control">
                        <label for="name" class="form-label">Nama Pemesan</label>
                        <input type="text" name="name" id="name"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required placeholder="Masukkan nama pemesan">
                    </div>
                    <div class="form-control">
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="tel" name="phone" id="phone"
                            class="border border-zinc-300 rounded-md px-4 h-12 focus:outline-none focus:ring-2 focus:ring-primary w-full"
                            required placeholder="Masukkan no. telepon">
                    </div> --}}
                </div>

                <div class="flex justify-end mt-6 gap-4">
                    <button type="button" data-form-prev
                        class="border-2 border-primary hover:bg-primary text-primary hover:text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                        Kembali
                    </button>
                    <button type="button" data-form-next
                        class="disabled:opacity-75 disabled:pointer-events-none bg-primary text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                        Selanjutnya
                    </button>
                </div>
            </div>

            <div data-form-index="2" class="hidden">
                <h4 class="text-lg font-semibold text-primary">Ringkasan Pesanan</h4>
                <p class="text-sm text-zinc-500">Silahkan periksa kembali pesanan Anda</p>

                <div class="mt-4">
                    <h5 class="font-medium text-primary">Jadwal Pesanan</h5>

                    <div class="grid md:grid-cols-3 grid-cols-2 gap-4 mt-2">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Tanggal</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-date"></span>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Durasi</span>
                            <span class="text-sm text-zinc-500">
                                <div>
                                    <span id="summary-time-start"></span> - <span id="summary-time-end"></span>
                                    (<span id="summary-duration"></span> Jam)
                                </div>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Tipe Pesanan</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-type"></span>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Fotografer</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-photographer">
                                    Tidak ada fotografer yang dipilih
                                </span>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Wasit</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-referee">
                                    Tidak ada wasit yang dipilih
                                </span>
                            </span>
                        </div>
                    </div>



                </div>

                <div class="mt-4">
                    <h5 class="font-medium text-primary">Rincian Biaya</h5>

                    <div class="grid md:grid-cols-3 grid-cols-2 gap-4 mt-2">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Lapangan</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-price-field">
                                    {{ format_rp($field->price_per_hour) }} / Jam
                                </span>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Wasit</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-referee-price">
                                    {{-- This will be updated dynamically --}}
                                    0
                                </span>
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold">Fotografer</span>
                            <span class="text-sm text-zinc-500">
                                <span id="summary-photographer-price">
                                    {{-- This will be updated dynamically --}}
                                    0
                                </span>
                            </span>
                        </div>

                        <div class="flex flex-col gap-1 col-span-4">
                            <span class="text-sm font-semibold">Total</span>
                            <span class="text-lg font-semibold text-primary">
                                <span id="summary-total-price">
                                    {{-- This will be updated dynamically --}}
                                    0
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-4">
                    <button type="button" data-form-prev
                        class="border-2 border-primary hover:bg-primary text-primary hover:text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                        Kembali
                    </button>
                    <button type="submit"
                        class="disabled:opacity-75 disabled:pointer-events-none bg-primary text-white px-4 py-2 rounded-md hover:brightness-90 duration-100 cursor-pointer">
                        Pesan
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let rendered = false

            function handleDateClick(info) {
                // Check if the clicked date is in the past
                const clickedDate = new Date(info.dateStr);
                const today = new Date();
                // Set the time to midnight for comparison
                today.setHours(0, 0, 0, 0);
                if (clickedDate < today) {
                    alert('Anda tidak dapat memilih tanggal di masa lalu.');
                    return;
                }

                // Set date input value to the clicked date
                $('#date').val(info.dateStr)
            }

            // Handle modal initial render
            document.querySelector("#schedule-modal").addEventListener('toggle', function(modal) {
                if (modal.detail.action === "open" && !rendered) {
                    const schedules = @json($schedules)

                    setTimeout(() => {
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            dateClick: handleDateClick,
                            events: schedules.map((schedule) => ({
                                ...schedule,
                                color: schedule.status ===
                                    'active' ?
                                    'var(--color-primary)' :
                                    'var(--color-zinc-500)'
                            })),

                            // Disable past dates
                            validRange: {
                                start: new Date()
                            },
                        });

                        calendar.render()
                        rendered = true

                        $('#loading').hide()
                    }, 200)
                }
            })

            // handle form next navigation
            $('[data-form-next]').on('click', async function() {
                const currentForm = $(this).closest('div[data-form-index]')
                const nextForm = currentForm.next('div[data-form-index]')
                const nextFormIndex = nextForm.attr('data-form-index')

                if (nextFormIndex === '1') {
                    if (
                        // Check if all main fields are filled
                        !$('#date').val() ||
                        !$('#time').val() ||
                        !$('#duration').val() ||

                        // Check if the  event-fields are filled if the type is not regular
                        ($('#type').val() !== 'regular' && (
                            !$('#title').val() ||
                            !$('#description').val()
                        ))
                    ) {
                        Swal.fire({
                            title: 'Perhatian',
                            text: 'Silahkan isi semua field yang diperlukan',
                            icon: 'warning',
                            confirmButtonColor: "var(--color-primary)",
                            confirmButtonText: 'OK',
                        });
                        return
                    }

                    document.querySelector('[data-form-next]').setAttribute('disabled', true)
                    document.querySelector('[data-form-next]').innerText = 'Loading...'

                    async function validateField() {
                        const date = $('#date').val()
                        const time = $('#time').val()
                        const duration = $('#duration').val()

                        if (!date || !time || !duration) {
                            Swal.fire({
                                title: 'Perhatian',
                                text: 'Silahkan isi semua field yang diperlukan',
                                icon: 'warning',
                                confirmButtonColor: "var(--color-primary)",
                                confirmButtonText: 'OK',
                            });
                            return false
                        }

                        const apiRoute = new URL('{{ route('api.fields.get-available') }}')

                        apiRoute.searchParams.append('date', date)
                        apiRoute.searchParams.append('time', time)
                        apiRoute.searchParams.append('duration', duration)
                        apiRoute.searchParams.append('field_id', '{{ $field->id }}')

                        try {
                            const response = await fetch(apiRoute)

                            if (response.status === 404) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Lapangan tidak tersedia pada waktu yang dipilih.',
                                    icon: 'error',
                                    confirmButtonColor: "var(--color-primary)",
                                    confirmButtonText: 'OK',
                                });
                                return false
                            }

                            return true
                        } catch (error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Gagal mengecek ketersediaan lapangan. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonColor: "var(--color-primary)",
                                confirmButtonText: 'OK',
                            });
                            return false
                        }
                    }

                    const success = await validateField()

                    document.querySelector('[data-form-next]').removeAttribute('disabled')
                    document.querySelector('[data-form-next]').innerText = 'Selanjutnya'

                    if (!success) {
                        return
                    }
                }

                if (nextForm.length) {
                    currentForm.hide()
                    nextForm.show()
                }

                if (nextFormIndex === '1') {
                    async function fetchServices() {
                        const date = $('#date').val()
                        const time = $('#time').val()
                        const duration = $('#duration').val()

                        $('#service-list').html(`
                         <div class="grid md:grid-cols-2 gap-4">
                            <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                            <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                            <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                            <div class="rounded-md h-24 bg-zinc-100 animate-pulse"></div>
                        </div>
                        `)

                        try {
                            const apiRoute = new URL('{{ route('api.services.get-available') }}')

                            apiRoute.searchParams.append('date', date)
                            apiRoute.searchParams.append('time', time)
                            apiRoute.searchParams.append('duration', duration)
                            apiRoute.searchParams.append('html', 'true')

                            const response = await fetch(apiRoute)
                            const responseBody = await response.json()

                            $('#service-list').html(responseBody.data)

                        } catch (error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Gagal memuat layanan. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonColor: "var(--color-primary)",
                                confirmButtonText: 'OK',
                            });
                        }
                    }

                    fetchServices()
                }

                if (nextFormIndex === '2') {
                    const date = $('#date').val()
                    const time = $('#time').val()
                    const duration = $('#duration').val()

                    const startTime = new Date(`${date}T${time}`)
                    const endTime = new Date(startTime.getTime() + duration * 60 * 60 * 1000)

                    $('#summary-date').text(date)
                    $('#summary-time-start').text(time)
                    $('#summary-time-end').text(endTime.toTimeString().slice(0, 5))
                    $('#summary-duration').text(duration)

                    const type = $('#type').val()

                    let typeText = ''

                    if (type === 'regular') {
                        typeText = 'Booking Lapangan'
                    } else if (type === 'open_match') {
                        typeText = 'Open Match'
                    } else if (type === 'trofeo') {
                        typeText = `Trofeo (${$('#max_teams').val()} Tim)`
                    }

                    // Get selected photographer and referee
                    const photographerName = $('input[name="photographer_id"]:checked').next('label')
                        .find(
                            'h3').text() || 'Tidak ada fotografer yang dipilih'
                    const refereeName = $('input[name="referee_id"]:checked').next('label').find('h3')
                        .text() || 'Tidak ada wasit yang dipilih'


                    $('#summary-photographer').text(photographerName)
                    $('#summary-referee').text(refereeName)

                    $('#summary-type').text(typeText)

                    // Calculate prices
                    const fieldPrice = {{ $field->price_per_hour }};
                    const photographerPrice = $('input[name="photographer_id"]:checked').data(
                        'service-price') || 0;
                    const refereePrice = $('input[name="referee_id"]:checked').data('service-price') ||
                        0;
                    const totalPrice = (fieldPrice + photographerPrice + refereePrice) * duration;

                    $('#summary-photographer-price').text(formatRp(photographerPrice * duration))
                    $('#summary-referee-price').text(formatRp(refereePrice * duration))
                    $('#summary-total-price').text(formatRp(totalPrice))
                    $('#summary-price-field').text(formatRp(fieldPrice * duration))


                }
            })

            function formatRp(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value);
            }

            // handle form prev navigation
            $('[data-form-prev]').on('click', function() {
                const currentForm = $(this).closest('div[data-form-index]')
                const prevForm = currentForm.prev('div[data-form-index]')

                if (prevForm.length) {
                    currentForm.hide()
                    prevForm.show()
                }
            })

            // handle booking type
            function updateTypeForms() {
                const selectedType = $('#type').val()

                $('#event-fields').hide()

                if (selectedType === 'trofeo' || selectedType === 'open_match') {
                    $('#event-fields').show()
                    $('[name="title"]').attr('required', true)
                    $('[name="description"]').attr('required', true)
                } else {
                    $('[name="title"]').removeAttr('required')
                    $('[name="description"]').removeAttr('required')
                }
            };

            $('#type').on('change', updateTypeForms);
            updateTypeForms()
        })
    </script>
@endpush
