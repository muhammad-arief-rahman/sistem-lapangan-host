@extends('mails.layouts.base')

@section('content')
    <h1>
        Mutasi Saldo Ditambahkan
    </h1>

    <p>
        Terjadi {{ $mutation->type }} pada saldo Anda. Berikut adalah detail mutasi:
    </p>

    <ul>
        <li>
            <strong>Jumlah</strong>: {{ number_format(abs($mutation->amount)) }}
        </li>
        <li>
            <strong>Waktu Transaksi</strong>: {{ $mutation->created_at->format('d-m-Y') }}
        </li>
    </ul>

    <p>
        Silahkan cek saldo Anda untuk memastikan perubahan telah diterapkan.
    </p>
@endsection
