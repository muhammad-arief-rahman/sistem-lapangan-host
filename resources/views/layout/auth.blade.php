@extends('layout.base')

@section('base-content')
    <div class="relative h-screen w-screen overflow-hidden grid place-items-center">
        <img src="{{ asset('assets/images/bg-login.webp') }}" alt="login-background"
            class="object-cover w-full h-full absolute">
        <div class="inset-0 absolute bg-primary/50"></div>

        <div
            class="bg-white max-w-xl w-[calc(100%-2rem)] mx-auto md:p-10 p-6 relative rounded-lg shadow-xl max-h-[90vh] overflow-y-auto">

            @yield('content')
        </div>
    </div>
@endsection
