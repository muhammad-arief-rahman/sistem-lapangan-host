@extends('mails.layouts.base')

@section('content')
    <h1>
        Permintaan Penarikan Saldo Baru
    </h1>

    <p>
        Pengguna <strong>{{ $withdrawal->user->name ?? $withdrawal->user->email }}</strong> telah mengajukan permintaan
        penarikan saldo sebesar <strong>{{ format_rp($withdrawal->amount) }}</strong>. Silakan verifikasi dan proses
        permintaan ini.
    </p>

    <ul>
        <li>
            <strong>Nama Pemohon</strong>: {{ $withdrawal->user->name ?? $withdrawal->user->email }}
        </li>
        <li>
            <strong>Jumlah Penarikan</strong>: {{ format_rp($withdrawal->amount) }}
        </li>
        <li>
            <strong>Tujuan Penarikan</strong>: {{ $withdrawal->account_type }} - {{ $withdrawal->account_number }}, a.n ({{ $withdrawal->account_name }})
        </li>
        <li>
            <strong>Tanggal Permintaan</strong>: {{ $withdrawal->created_at->format('d-m-Y') }}
        </li>
    </ul>

    <p>
        Silahkan akses halaman <a href="{{ route('dashboard.withdrawal.index') }}">Penarikan</a> untuk melihat detail.
    </p>
@endsection
