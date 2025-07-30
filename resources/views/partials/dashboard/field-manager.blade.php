<div class="dashboard-cards">
    <x-dashboard-card title="Total Lapangan" value="{{ $cardData->totalFields }}"
        href="{{ route('dashboard.field.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-flag"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Saldo" value="{{ format_rp($cardData->balance) }}"
        href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-wallet"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Penarikan Menunggu" value="{{ $cardData->totalPendingWithdrawals }}"
        href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-spinner"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Total Booking" value="{{ $cardData->totalBookings }}"
        href="{{ route('dashboard.booking.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-calendar-check"></i>
        </x-slot:icon>
    </x-dashboard-card>
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="col-span-3 bg-white shadow-main rounded-lg p-4 md:p-6 flex flex-col gap-4">
        <h2 class="text-lg font-medium">Daftar Booking</h2>
        @include('partials.booking-table', ['bookings' => $bookings])
    </div>
</div>
