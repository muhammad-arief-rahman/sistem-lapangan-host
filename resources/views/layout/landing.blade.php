@extends('layout.base')


@section('base-content')
    <div class="fixed inset-0">
        <img src="{{ asset('assets/images/bg-main.webp') }}" alt="main-background"
            class="object-cover bg-zinc-500 w-full h-full absolute">
        <div class="inset-0 absolute bg-primary/50"></div>
    </div>
    <div class="min-h-screen flex flex-col">
        <x-landing-header />
        <main class="grow relative isolate">
            <div class="absolute h-64 w-full bg-gradient-to-b from-zinc-900/25 to-transparent -z-10"></div>
            @yield('content')
        </main>
    </div>
@endsection
