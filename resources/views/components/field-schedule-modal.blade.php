<x-modal id="field-schedule-modal" class="max-w-4xl">

    <div class="relative">
        <h4 class="text-lg font-semibold text-primary">Jadwal Lapangan</h4>

        <p class="text-sm text-zinc-500">
            Silahkan klik pada tanggal untuk menambahkan jadwal baru pada hari tersebut.
        </p>

        <div class="absolute inset-0 grid place-items-center text-2xl font-medium" id="loading">
            Loading...
        </div>

        <div class="h-full bg-white mt-4 md:max-h-96 overflow-y-auto">
            <div id="field-scedule-modal-calendar" class="clickable-calendar md:aspect-[4/3] aspect-video"></div>
        </div>

        <form action="{{ route('dashboard.field-schedule.store') }}" method="POST" class="flex flex-col gap-4 mt-4">
            @csrf
            <h2 class="text-lg font-semibold text-primary">Buat Jadwal Baru</h2>

            <input type="hidden" name="field_id" id="field_id">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required
                        placeholder="Masukkan tanggal">
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
                        class="w-full border border-zinc-200 rounded-lg px-4 h-12" required
                        placeholder="Masukkan durasi" value="1">
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
    </div>
</x-modal>

@push('scripts')
    <script>
        (() => {
            let rendered = false;
            let schedules = []
            let calendar = null

            document.addEventListener('DOMContentLoaded', function() {

                document.querySelector("#field-schedule-modal").addEventListener('toggle',
                    function(modal) {
                        if (modal.detail.action === "open" && !rendered) {
                            console.log("Opening schedule modal");

                            setTimeout(() => {
                                var calendarEl = document.getElementById(
                                    'field-scedule-modal-calendar');
                                calendar = new FullCalendar.Calendar(calendarEl, {
                                    dateClick: handleDateClick,
                                    events: [],

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

                // Set listeners to buttons
                document.querySelectorAll('[data-modal="field-schedule-modal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        let data = null

                        try {
                            data = JSON.parse(button.dataset.fieldData);
                            schedules = data.schedules || [];
                        } catch (e) {
                            console.error("Invalid field data:", e);
                            return;
                        }

                        const fieldIdInput = document.querySelector('#field_id');
                        fieldIdInput.value = data.id;

                        const calendarData = data.schedules.map((schedule) => {
                            const startTime = new Date(schedule.start_datetime);
                            const endTime = new Date(schedule.end_datetime);

                            // Format to Y-m-d
                            const startDate = startTime.toISOString().split('T')[0];
                            const endDate = endTime.toISOString().split('T')[0];

                            const startHour = startTime.getHours().toString().padStart(
                                2, '0');
                            const startMinute = startTime.getMinutes().toString()
                                .padStart(2, '0');
                            const endHour = endTime.getHours().toString().padStart(2,
                                '0');
                            const endMinute = endTime.getMinutes().toString().padStart(
                                2, '0');

                            return {
                                'title': `${startHour}:${startMinute} - ${endHour}:${endMinute}`,
                                'start': startDate,
                                'end': endDate,
                                'status': schedule.status,
                                'color': schedule.status === 'active' ?
                                    'var(--color-primary)' : 'var(--color-zinc-500)',
                            }
                        });

                        // Clear previous events
                        setTimeout(() => {
                            if (calendar) {
                                const allEvents = calendar.getEvents();
                                allEvents.forEach(event => event.remove());

                                calendarData.forEach(event => calendar.addEvent(event))
                            }

                        }, 200)
                    });
                });

                function updateTimeRange() {
                    const tanggalInput = document.querySelector('#tanggal');
                    const jamInput = document.querySelector('#jam');
                    const durationInput = document.querySelector('#duration');

                    const startDatetime = new Date(tanggalInput.value + 'T' + jamInput.value);
                    const endDatetime = new Date(startDatetime.getTime() + (durationInput.value * 60 * 60 *
                        1000));

                    // Format date as DDDD, MMMM D, YYYY indonesia
                    document.querySelector('#start-datetime').textContent = startDatetime.toLocaleString(
                        'id-ID', {
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
                    const tanggalInput = document.querySelector('#tanggal');
                    tanggalInput.value = info.dateStr;
                    updateTimeRange();
                }

                // Add listeners for date and time inputs
                document.querySelector('#tanggal').addEventListener('change', updateTimeRange);
                document.querySelector('#jam').addEventListener('change', updateTimeRange);
                document.querySelector('#duration').addEventListener('input', updateTimeRange);
            });
        })()
    </script>
@endpush
