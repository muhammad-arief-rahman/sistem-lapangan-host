@extends('mails.layouts.base')

@section('content')
    <h1>
        Penarikan Ditolak
    </h1>

    <p>
        Penarikan anda sebanyak {{ $withdrawal->amount }} telah ditolak pada
        {{ $withdrawal->created_at->translatedFormat('d M Y H:i') }}. Alasan: {{ $withdrawal->notes }}
    </p>

    <p>
        Silahkan ajukan kembali penarikan apabila diperlukan.
    </p>
@endsection
