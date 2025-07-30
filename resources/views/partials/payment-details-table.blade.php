<table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main">
    <thead>
        <tr>
            <th>No</th>
            <th>Pesanan</th>
            @if (authorized('super_admin'))
                <th>Pemesan</th>
            @endif
            <th>Tanggal Pesanan</th>
            <th>Jumlah Pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paymentDetails as $paymentDetail)
            <tr class="[&>td]:whitespace-nowrap">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $paymentDetail->payment->booking->field->name }}</td>
                @if (authorized('super_admin'))
                    <td>{{ $paymentDetail->payment->booking->user->name }}</td>
                @endif
                <td>{{ $paymentDetail->created_at->translatedFormat('l, d F Y, H:i') }}</td>
                <td>
                    <span>{{ format_rp($paymentDetail->amount) }}</span>
                </td>
                <td>
                    @if ($paymentDetail->status === 'pending')
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @elseif ($paymentDetail->status === 'completed')
                        <span class="badge badge-success">Pembayaran Selesai</span>
                    @elseif ($paymentDetail->status === 'failed')
                        <span class="badge badge-danger">Pembayaran Gagal</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('dashboard.payment.show', ['id' => $paymentDetail->id]) }}" class="contents">
                        <button class="btn btn-primary">
                            @if (auth()->user()->role !== 'super_admin' && $paymentDetail->status === 'pending')
                                Bayar
                            @else
                                Lihat
                            @endif
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
