@extends('mails.layouts.base')

@section('content')
    <h1>
        Bookingan Baru
    </h1>

    <p>
        Pengguna {{ $booking->user->name }} telah melakukan booking layanan anda untuk pertandingan pada lapangan {{ $booking->field->name }}.
        Berikut adalah detail booking:
    </p>

    <ul>
        <li>
            <strong>Lapangan</strong>: {{ $booking->field->name }}
        </li>
        <li>
            <strong>Tanggal Pesanan</strong>: {{ $booking->created_at->format('d-m-Y') }}
        </li>
        <li>
            <strong>Waktu Tanding</strong>: {{ $booking->fieldSchedule->getScheduleDateString() }}
        </li>
    </ul>

    <p>
        Silahkan cek jadwal pertandingan di dashboard untuk detail lebih lanjut.
    </p>
@endsection
