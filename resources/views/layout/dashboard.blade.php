@extends('layout.base')

@section('base-content')
    <div class="h-screen w-screen overflow-hidden flex">
        <x-dashboard-sidebar />

        <div class="flex flex-col grow">
            <x-dashboard-header>
                @yield('title')
            </x-dashboard-header>
            <main class="overflow-y-auto grow [scrollbar-gutter:stable]">
                @yield('content')
            </main>
        </div>
    </div>
@endsection
