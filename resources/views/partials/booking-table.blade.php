<table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main ">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Lapangan</th>
            <th>Tipe Pesanan</th>
            <th>Status Pesanan</th>
            <th>Jasa</th>
            <th>Waktu Pertandingan</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
            {{-- <th>Pembayaran</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr class="[&>td]:whitespace-nowrap">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->field->name }}</td>
                <td>{{ $booking->getTypeLabel() }}</td>
                <td>
                    @if ($booking->status === 'pending')
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @elseif ($booking->status === 'confirmed')
                        <span class="badge badge-success">Aktif</span>
                    @elseif ($booking->status === 'cancelled')
                        <span class="badge badge-error">Dibatalkan</span>
                    @endif
                </td>
                <td>
                    @if ($booking->bookedServices->count() > 0)
                        <ul class="list-disc pl-4">
                            @foreach ($booking->bookedServices as $bookedService)
                                <li>
                                    {{ $bookedService->service->user->name }}
                                    ({{ $bookedService->service->getTypeLabel() }})
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-gray-500">-Tidak ada jasa-</span>
                    @endif
                </td>
                <td>
                    {{ $booking->fieldSchedule->getScheduleDateString() }}
                </td>
                <td>
                    <span>
                        {{ format_rp($booking->payment->total_amount) }}
                    </span>
                    @if ($booking->type !== 'regular')
                        <span>
                            ({{ format_rp($booking->getPerPersonPrice()) }} / Tim)
                        </span>
                    @endif
                </td>

                <td>
                    @if ($booking->payment->status === 'pending')
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @elseif ($booking->payment->status === 'completed')
                        <span class="badge badge-success">Sudah Dibayar</span>
                    @elseif ($booking->payment->status === 'partial')
                        <span class="badge badge-info">Sebagian Dibayar</span>
                    @elseif ($booking->payment->status === 'failed')
                        <span class="badge badge-error">Gagal</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
