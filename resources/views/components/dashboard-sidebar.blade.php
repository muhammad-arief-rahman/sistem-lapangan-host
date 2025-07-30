@php
    $user = auth()->user();
    $role = $user->role;

    function isActive($route)
    {
        if ($route === 'dashboard.index') {
            return request()->routeIs($route) ? 'bg-white text-primary' : 'bg-primary';
        }

        return request()->routeIs($route) ? 'bg-white text-primary' : 'bg-primary';
    }

    $menu = [
        [
            'route' => 'dashboard.index',
            'icon' => Blade::render('<x-icons.squares />'),
            'label' => 'Dashboard',
        ],
        [
            'route' => 'dashboard.field.index',
            'icon' => Blade::render('<x-icons.ball />'),
            'label' => 'Lapangan',
            'roles' => ['super_admin', 'field_manager'],
        ],
        [
            'route' => 'dashboard.booking.index',
            'icon' => Blade::render('<x-icons.receipt />'),
            'label' => 'Booking',
            'roles' => ['super_admin', 'field_manager', 'community'],
        ],
        [
            'route' => 'dashboard.mutation.index',
            'icon' => Blade::render('<x-icons.money />'),
            'label' => 'Pendapatan',
        ],
        // [
        //     'route' => 'dashboard.mutation.index',
        //     'icon' => Blade::render('<x-icons.money />'),
        //     'label' => 'Pendapatan',
        // ],
        [
            'route' => 'dashboard.withdrawal.index',
            'icon' => '<i class="fa-solid fa-money-bill-transfer"></i>',
            'label' => 'Penarikan',
        ],
        [
            'route' => 'dashboard.payment.index',
            'icon' => Blade::render('<x-icons.transaction />'),
            'label' => 'Pembayaran',
            'roles' => ['super_admin', 'community'],
        ],
        [
            'route' => 'dashboard.events.index',
            'icon' => Blade::render('<x-icons.flag />'),
            'label' => 'Pertandingan',
        ],
        [
            'route' => 'dashboard.match-photos.index',
            'icon' => '<i class="fa-solid fa-camera"></i>',
            'label' => 'Foto Pertandingan',
            'roles' => ['photographer', 'super_admin', 'community'],
        ],
        [
            'route' => 'dashboard.user.index',
            'icon' => Blade::render('<x-icons.user />'),
            'label' => 'Pengguna',
            'roles' => ['super_admin'],
        ],
        [
            'route' => 'dashboard.service-schedule.index',
            'icon' => Blade::render('<x-icons.calendar />'),
            'label' => 'Jadwal Layanan',
            'roles' => ['referee', 'photographer', 'super_admin'],
        ],
        [
            'route' => 'dashboard.profile.index',
            'icon' => '<i class="fa-solid fa-address-card"></i>',
            'label' => 'Profil',
            'roles' => ['field_manager', 'photographer', 'referee', 'community'],
        ],
        [
            'route' => 'dashboard.profile.index',
            'icon' => '<i class="fa-solid fa-gear"></i>',
            'label' => 'Pengaturan',
            'roles' => ['super_admin']
        ],
    ];
@endphp

<aside
    class="md:w-64 w-0 md:z-0 z-50 bg-primary shrink-0 overflow-hidden data-[visible='true']:w-64 fixed md:relative md:block h-screen duration-200 transition-all ">
    <div class="w-64">
        <div class="h-24 grid place-items-center">
            <a href="{{ $role === 'community' ? route('home') : route('dashboard.index') }}">
                <h1 class="text-xl font-semibold text-white px-6 pt-4">
                    {{ config('app.name') }}
                </h1>
            </a>
            {{-- <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="w-32 h-32"> --}}
        </div>
        <div class="flex flex-col gap-2 p-4 text-white">
            @foreach ($menu as $item)
                @if (authorized(...$item['roles'] ?? [$role]))
                    <x-sidebar-item :route="$item['route']">
                        <x-slot:icon>
                            {!! $item['icon'] !!}
                        </x-slot:icon>
                        {{ $item['label'] }}
                    </x-sidebar-item>
                @endif
            @endforeach
        </div>
    </div>
</aside>
<div class="h-screen z-30 w-screen transition-all bg-zinc-900/0 backdrop-blur-none fixed invisible duration-200 opacity-0"
    id="dashboard-sidebar-backdrop"></div>
