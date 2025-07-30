@php
    function isRouteActive($routeName)
    {
        return request()->routeIs($routeName);
    }
@endphp


<header class="h-16 md:h-20 sticky top-0 bg-primary flex items-center z-10 group/header" id="header">
    <div class="md:absolute z-20 group-[&[data-menu-active='true']]/header:h-fit md:inset-0 top-16 md:top-0 fixed w-full md:grid md:place-items-center overflow-hidden h-0 md:h-full"
        id="header-menu">
        <div
            class="w-full md:bg-transparent bg-primary text-white justify-center md:items-center items-start flex  flex-col md:flex-row  ">
            <a class="md:px-8 px-6 py-3 w-full md:w-fit md:py-2 group " href="{{ route('home') }}">
                <div class="relative">
                    <span>Booking Lapangan</span>
                    <div
                        class="{{ isRouteActive('home') ? 'opacity-100' : '' }} opacity-0 group-hover:opacity-100 duration-100 absolute top-full h-0.5 rounded-full bg-white w-full left-0 translate-y-1">
                    </div>
                </div>
            </a>
            <a class="md:px-8 px-6 py-3 w-full md:w-fit md:py-2 group" href="{{ route('event.open-matches.index') }}">
                <div class="relative">
                    <span>Open Match</span>
                    <div
                        class="{{ isRouteActive('event.open-matches.index') ? 'opacity-100' : '' }} opacity-0 group-hover:opacity-100 duration-100 absolute top-full h-0.5 rounded-full bg-white w-full left-0 translate-y-1">
                    </div>
                </div>
            </a>
            <a class="md:px-8 px-6 py-3 w-full md:w-fit md:py-2 group" href="{{ route('event.trofeos.index') }}">
                <div class="relative">
                    <span>Trofeo</span>
                    <div
                        class="{{ isRouteActive('event.trofeos.index') ? 'opacity-100' : '' }} opacity-0 group-hover:opacity-100 duration-100 absolute top-full h-0.5 rounded-full bg-white w-full left-0 translate-y-1">
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="w-content mx-auto relative flex items-center justify-between">
        <div class="xl:text-3xl text-lg font-semibold text-white relative z-30">
            {{ config('app.name') }}
        </div>

        <div class="flex gap-2 text-white relative z-30">
            <a href="{{ route('dashboard.notification.index') }}"
                class="size-12 bg-primary grid place-items-center hover:brightness-90 cursor-pointer rounded-full relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-bell-icon lucide-bell">
                    <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                    <path
                        d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                </svg>

                @if (auth()->user()->notifications()->count() > 0)
                    <span
                        class="absolute -top-1 -left-1 bg-red-500 text-white text-xs font-semibold rounded-full size-5 grid place-items-center">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            @auth
                <x-avatar-profile>
                    <x-slot name="trigger">
                        <button
                            class="size-12 bg-primary grid place-items-center hover:brightness-90 cursor-pointer rounded-full">
                            <svg width="24" height="24" viewBox="0 0 30 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 8.75C10 7.42392 10.5268 6.15215 11.4645 5.21447C12.4021 4.27678 13.6739 3.75 15 3.75C16.3261 3.75 17.5979 4.27678 18.5355 5.21447C19.4732 6.15215 20 7.42392 20 8.75C20 10.0761 19.4732 11.3479 18.5355 12.2855C17.5979 13.2232 16.3261 13.75 15 13.75C13.6739 13.75 12.4021 13.2232 11.4645 12.2855C10.5268 11.3479 10 10.0761 10 8.75ZM10 16.25C8.3424 16.25 6.75269 16.9085 5.58058 18.0806C4.40848 19.2527 3.75 20.8424 3.75 22.5C3.75 23.4946 4.14509 24.4484 4.84835 25.1517C5.55161 25.8549 6.50544 26.25 7.5 26.25H22.5C23.4946 26.25 24.4484 25.8549 25.1517 25.1517C25.8549 24.4484 26.25 23.4946 26.25 22.5C26.25 20.8424 25.5915 19.2527 24.4194 18.0806C23.2473 16.9085 21.6576 16.25 20 16.25H10Z"
                                    fill="currentColor" />
                            </svg>
                        </button>
                    </x-slot>
                </x-avatar-profile>
            @else
                <a href="{{ route('login') }}"
                    class="bg-primary text-white grid place-items-center px-4 py-2 rounded-lg hover:brightness-90 duration-100">Masuk</a>
            @endauth

            <button id="header-menu-toggle"
                class="size-12 bg-primary md:hidden grid place-items-center text-white hover:brightness-90 cursor-pointer rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-menu-icon lucide-menu">
                    <path d="M4 12h16" />
                    <path d="M4 18h16" />
                    <path d="M4 6h16" />
                </svg>
            </button>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        (() => {
            const header = document.getElementById('header');

            function toggleHeaderMenu() {
                const isActive = header.dataset.menuActive === 'true';
                header.dataset.menuActive = isActive ? 'false' : 'true';
            }

            document.getElementById('header-menu-toggle').addEventListener('click', toggleHeaderMenu);
        })();
    </script>
@endpush
