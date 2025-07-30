@extends('mails.layouts.base')

@section('content')
    <h1>Booking Telah Dibuat</h1>

    <p>
        Terima kasih telah melakukan booking. Berikut adalah detail booking Anda:
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
        Silahkan melakukan pembayaran untuk mengkonfirmasi booking Anda.
    </p>
@endsection
