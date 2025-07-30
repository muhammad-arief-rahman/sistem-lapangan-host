@extends('mails.layouts.base')

@section('content')
    <h1>
        Pendaftaran Open Match
    </h1>

    <p>
        Komunitas '{{ $registeredUser->name }}' mendaftar untuk open match yang anda buat.
    </p>

    <p>
        Pertandingan dapat dilakukan apabila pembayaran oleh komunitas tersebut telah dilakukan.
    </p>
@endsection
