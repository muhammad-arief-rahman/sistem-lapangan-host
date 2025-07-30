@extends('mails.layouts.base')

@section('content')
    <h1>
        Jadwal Layanan Telah Dikonfirmasi
    </h1>

    <p>
        Pembayaran untuk layanan Anda pada sebuah booking lapangan telah dikonfirmasi. <br>
        Berikut adalah detail booking Anda:
    </p>

    <ul>
        <li>
            <strong>Lapangan</strong>: {{ $paymentDetail->payment->booking->field->name }}
        </li>
        <li>
            <strong>Tanggal Pesanan</strong>: {{ $paymentDetail->payment->booking->created_at->format('d-m-Y') }}
        </li>
        <li>
            <strong>Waktu Tanding</strong>: {{ $paymentDetail->payment->booking->fieldSchedule->getScheduleDateString() }}
        </li>
    </ul>

    <p>
        Silahkan cek jadwal Anda pada dashboard.
    </p>
@endsection
