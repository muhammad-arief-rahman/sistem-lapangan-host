<table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main ">
    <thead>
        <tr>
            <th>No</th>
            <th>Tipe</th>
            <th>Jadwal</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $schedule)
            <tr class="[&>td]:whitespace-nowrap">
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($schedule->bookedService)
                        <span class="badge badge-info">Booking</span>
                    @else
                        <span class="badge badge-secondary">Jadwal</span>
                    @endif
                </td>
                <td>{{ $schedule->getScheduleDateString() }}</td>
                <td>
                    @if ($schedule->status === 'active')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-error">Tidak Aktif</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
