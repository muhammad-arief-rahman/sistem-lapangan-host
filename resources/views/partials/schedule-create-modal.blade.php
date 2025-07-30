@push('root')
    <x-modal id="modal-create">
        <form action="{{ route('dashboard.service-schedule.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <h2 class="text-lg font-semibold text-primary">Buat Jadwal Baru</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required placeholder="Masukkan tanggal">
                </div>
                <div class="form-control">
                    <label for="jam" class="form-label">Jam</label>
                    <input type="time" name="jam" id="jam"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required placeholder="Masukkan jam"
                        step="3600" value="{{ date('H:00', strtotime('+1 hour')) }}">
                </div>
                <div class="form-control">
                    <label for="duration" class="form-label">Durasi (Jam)</label>
                    <input type="number" name="duration" id="duration"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required placeholder="Masukkan durasi"
                        value="1">
                </div>
            </div>
            <div class="flex h-fit flex-col gap-2">
                <div class="form-label">Rentang Jadwal</div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <div class="bg-primary size-4 rounded-full grid place-items-center">
                            <div class="size-2 bg-white rounded-full"></div>
                        </div>
                        <span class=" text-lg" id="start-datetime">Belum Diatur</span>
                    </div>
                    <div class="flex items-center gap-2 size-4 justify-center">
                        <i class="fa-solid fa-arrow-down text-primary"></i>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-primary size-4 rounded-full grid place-items-center">
                        </div>
                        <span class=" text-lg" id="end-datetime">Belum Diatur</span>
                    </div>
                </div>
            </div>
            <button type="submit"
                class="bg-primary mt-4 self-end text-white px-6 py-2 rounded-lg cursor-pointer duration-100 h-12">
                Simpan
            </button>
        </form>
    </x-modal>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateTimeRange() {
                const tanggalInput = document.querySelector('#tanggal');
                const jamInput = document.querySelector('#jam');
                const durationInput = document.querySelector('#duration');

                const startDatetime = new Date(tanggalInput.value + 'T' + jamInput.value);
                const endDatetime = new Date(startDatetime.getTime() + (durationInput.value * 60 * 60 * 1000));

                // Format date as DDDD, MMMM D, YYYY indonesia
                document.querySelector('#start-datetime').textContent = startDatetime.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });

                document.querySelector('#end-datetime').textContent = endDatetime.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }

            function handleDateClick(info) {
                const modal = document.querySelector('#modal-create');

                const tanggalInput = modal.querySelector('#tanggal');

                tanggalInput.value = info.dateStr;

                dispatchEvent(new CustomEvent('openModal', {
                    detail: {
                        id: 'modal-create',
                    }
                }))

                updateTimeRange();
            }

            const schedules = @json($schedules)

            var calendarEl = document.querySelector('#calendar');

            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    events: schedules.map(schedule => ({
                        ...schedule,
                        color: schedule.status === 'active' ? 'var(--color-primary)' :
                            'var(--color-zinc-500)'
                    })),
                    height: '100%',
                    {{ auth()->user()->role !== 'super_admin' ? 'dateClick: handleDateClick,' : '' }}
                });

                calendar.render()
            }

            document.querySelector('#tanggal').addEventListener('change', updateTimeRange);
            document.querySelector('#jam').addEventListener('change', updateTimeRange);
            document.querySelector('#duration').addEventListener('input', updateTimeRange);
        })
    </script>
@endpush
