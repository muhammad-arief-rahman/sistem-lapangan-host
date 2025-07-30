@extends('mails.layouts.base')

@section('content')
    <h1>
        Pendaftaran Trofeo
    </h1>

    <p>
        Komunitas '{{ $registeredUser->name }}' mendaftar untuk trofeo yang anda buat.
    </p>

    <p>
        Pertandingan dapat dilakukan apabila pembayaran oleh semua komunitas pada pertandingan telah dilakukan.
    </p>
@endsection
