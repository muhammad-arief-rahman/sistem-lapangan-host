<header class="md:h-20 h-16 bg-white shadow-main px-4 md:px-12 flex items-center justify-between shrink-0">
    <div class="flex items-center gap-4">
        <button class="size-8 md:hidden grid place-items-center" id="sidebar-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-menu-icon lucide-menu">
                <path d="M4 12h16" />
                <path d="M4 18h16" />
                <path d="M4 6h16" />
            </svg>
        </button>

        <h1 class="text-md md:text-2xl font-medium">
            @if (isset($slot) && $slot != '')
                {{ $slot }}
            @else
                Dashboard
            @endif
        </h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('dashboard.notification.index') }}"
            class="size-12 cursor-pointer relative hover:brightness-90 duration-100 rounded-full p-3 grid place-items-center  {{ request()->routeIs('dashboard.notification.index') ? 'bg-primary text-white' : 'bg-white text-zinc-900' }}">
            <svg class="size-full" width="30" height="30" viewBox="0 0 30 30" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 2.5C12.6793 2.5 10.4537 3.42187 8.81279 5.06282C7.17185 6.70376 6.24997 8.92936 6.24997 11.25V15.66C6.25015 15.8539 6.20522 16.0452 6.11872 16.2188L3.97247 20.51C3.86763 20.7197 3.81813 20.9526 3.82866 21.1868C3.83919 21.421 3.90941 21.6485 4.03266 21.8479C4.1559 22.0473 4.32806 22.2119 4.53281 22.326C4.73755 22.4401 4.96807 22.5 5.20247 22.5H24.7975C25.0319 22.5 25.2624 22.4401 25.4671 22.326C25.6719 22.2119 25.8441 22.0473 25.9673 21.8479C26.0905 21.6485 26.1608 21.421 26.1713 21.1868C26.1818 20.9526 26.1323 20.7197 26.0275 20.51L23.8825 16.2188C23.7955 16.0453 23.7502 15.854 23.75 15.66V11.25C23.75 8.92936 22.8281 6.70376 21.1872 5.06282C19.5462 3.42187 17.3206 2.5 15 2.5ZM15 26.25C14.2242 26.2504 13.4673 26.0102 12.8337 25.5624C12.2002 25.1147 11.7211 24.4814 11.4625 23.75H18.5375C18.2789 24.4814 17.7998 25.1147 17.1662 25.5624C16.5326 26.0102 15.7758 26.2504 15 26.25Z"
                    fill="currentColor" />
            </svg>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <span
                    class="absolute -top-1 -left-1 bg-red-500 text-white text-xs font-semibold rounded-full size-5 grid place-items-center">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
        <x-avatar-profile>
            <x-slot name="trigger">
                <button
                    class="size-12 cursor-pointer bg-white hover:brightness-90 duration-100 rounded-full p-3 grid place-items-center text-zinc-900">
                    <svg class="size-full" width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 8.75C10 7.42392 10.5268 6.15215 11.4645 5.21447C12.4021 4.27678 13.6739 3.75 15 3.75C16.3261 3.75 17.5979 4.27678 18.5355 5.21447C19.4732 6.15215 20 7.42392 20 8.75C20 10.0761 19.4732 11.3479 18.5355 12.2855C17.5979 13.2232 16.3261 13.75 15 13.75C13.6739 13.75 12.4021 13.2232 11.4645 12.2855C10.5268 11.3479 10 10.0761 10 8.75ZM10 16.25C8.3424 16.25 6.75269 16.9085 5.58058 18.0806C4.40848 19.2527 3.75 20.8424 3.75 22.5C3.75 23.4946 4.14509 24.4484 4.84835 25.1517C5.55161 25.8549 6.50544 26.25 7.5 26.25H22.5C23.4946 26.25 24.4484 25.8549 25.1517 25.1517C25.8549 24.4484 26.25 23.4946 26.25 22.5C26.25 20.8424 25.5915 19.2527 24.4194 18.0806C23.2473 16.9085 21.6576 16.25 20 16.25H10Z"
                            fill="currentColor" />
                    </svg>
                </button>
            </x-slot>
        </x-avatar-profile>
    </div>
</header>

@push('scripts')
    <script>
        (() => {
            const sidebar = document.querySelector('aside');
            const sidebarBackdrop = document.querySelector('#dashboard-sidebar-backdrop');

            function toggleSidebar() {

                const sidebarVisible = sidebar.dataset.visible;
                const activeState = sidebarVisible === 'true' ? 'false' : 'true';

                sidebar.dataset.visible = activeState;
                document.querySelector('body').dataset.sidebarVisible = activeState;

                if (activeState === 'true') {
                    sidebarBackdrop.classList.remove('invisible');
                    sidebarBackdrop.classList.remove('backdrop-blur-none');
                    sidebarBackdrop.classList.remove('opacity-0');
                    sidebarBackdrop.classList.remove('bg-zinc-900/0');
                    sidebarBackdrop.classList.add('bg-zinc-900/10');
                    sidebarBackdrop.classList.add('opacity-100');
                    sidebarBackdrop.classList.add('backdrop-blur-xs');
                    document.querySelector('body').classList.add('overflow-hidden');
                } else {
                    sidebarBackdrop.classList.add('invisible');
                    sidebarBackdrop.classList.add('backdrop-blur-none');
                    sidebarBackdrop.classList.add('opacity-0');
                    sidebarBackdrop.classList.add('bg-zinc-900/0');
                    sidebarBackdrop.classList.remove('bg-zinc-900/10');
                    sidebarBackdrop.classList.remove('opacity-100');
                    sidebarBackdrop.classList.remove('backdrop-blur-xs');
                    document.querySelector('body').classList.remove('overflow-hidden');
                }
            }

            document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);
            sidebarBackdrop.addEventListener('click', toggleSidebar)

        })()
    </script>
@endpush
