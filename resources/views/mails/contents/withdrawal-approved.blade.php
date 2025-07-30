@extends('mails.layouts.base')

@section('content')
    <h1>
        Penarikan Disetujui
    </h1>

    <p>
        Penarikan anda sebanyak {{ $withdrawal->amount }} telah disetujui pada
        {{ $withdrawal->created_at->translatedFormat('d M Y H:i') }}.
    </p>

    <p>
        Silahkan balas email ini jika ada pertanyaan atau butuh bantuan lebih lanjut.
    </p>
@endsection
