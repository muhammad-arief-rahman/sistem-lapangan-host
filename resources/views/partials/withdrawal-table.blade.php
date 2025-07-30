<table class="simple-datatable bg-white rounded-lg overflow-hidden shadow-main">
    <thead>
        <tr>
            <th>No</th>
            @if (auth()->user()->role === 'super_admin')
                <th>Nama</th>
            @endif
            <th>Jumlah</th>
            <th>Tujuan Penarikan</th>
            <th>Diajukan Pada</th>
            <th>Status</th>
            <th>Pesan</th>
            <th>Disetujui Pada</th>
            <th>Bukti Pengiriman</th>
            @if (authorized('super_admin'))
                <th>Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($withdrawals as $withdrawal)
            <tr class="[&>td]:whitespace-nowrap">
                <td>{{ $loop->iteration }}</td>
                @if (auth()->user()->role === 'super_admin')
                    <td>{{ $withdrawal->user->name }}</td>
                @endif
                <td>{{ format_rp($withdrawal->amount) }}</td>
                <td>{{ $withdrawal->account_type }}, a.n {{ $withdrawal->account_name }}
                    ({{ $withdrawal->account_number }})
                </td>
                <td>{{ $withdrawal->created_at->translatedFormat('d/m/Y H:i') }}</td>
                <td>
                    @if ($withdrawal->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif ($withdrawal->status === 'completed')
                        <span class="badge badge-success">Selesai</span>
                    @elseif ($withdrawal->status === 'failed')
                        <span class="badge badge-error">Ditolak</span>
                    @else
                        <span class="badge badge-secondary">Tidak Diketahui</span>
                    @endif
                </td>
                <td>{{ $withdrawal->notes ?? '-' }}</td>
                <td>
                    @if ($withdrawal->approved_at)
                        {{ $withdrawal->approved_at->translatedFormat('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($withdrawal->transfer_proof)
                        <a href="{{ $withdrawal->getImageUrl() }}" target="_blank" class="text-primary hover:underline">
                            Lihat Bukti
                        </a>
                    @else
                        -
                    @endif
                </td>
                @if (authorized('super_admin'))
                    <td class="flex gap-2">
                        @if ($withdrawal->status === 'pending')
                            <button class="btn btn-primary process-withdrawal" data-id="{{ $withdrawal->id }}"
                                data-amount="{{ format_rp($withdrawal->amount) }}"
                                data-name="{{ $withdrawal->user->name }}"
                                data-account-type="{{ $withdrawal->account_type }}"
                                data-account-name="{{ $withdrawal->account_name }}"
                                data-account-number="{{ $withdrawal->account_number }}"
                                data-created-at="{{ $withdrawal->created_at->translatedFormat('d/m/Y H:i') }}">
                                <i class="fa-solid fa-check"></i>
                                Proses
                            </button>
                            <button class="btn btn-secondary reject-withdrawal" data-id="{{ $withdrawal->id }}">
                                <i class="fa-solid fa-xmark"></i>
                                Tolak
                            </button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

@if (authorized('super_admin'))
    @push('root')
        @include('partials.withdrawal.withdrawal-approve-modal')
        @include('partials.withdrawal.withdrawal-reject-modal')
    @endpush
@endif
