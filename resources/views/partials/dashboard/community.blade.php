<div class="dashboard-cards">
    <x-dashboard-card title="Total Booking" value="{{ $cardData->totalBookings }}"
        href="{{ route('dashboard.booking.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-calendar-check"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Booking Reguler" value="{{ $cardData->totalRegularBookings }}"
        href="{{ route('dashboard.booking.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-calendar-days"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Open Match" value="{{ $cardData->totalOpenMatches }}"
        href="{{ route('dashboard.events.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-soccer-ball"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Trofeo" value="{{ $cardData->totalTrofeos }}" href="{{ route('dashboard.events.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-trophy"></i>
        </x-slot:icon>
    </x-dashboard-card>
</div>

<div class="flex flex-col gap-4 bg-white rounded-lg p-4 md:p-6 shadow-main">
    <h2 class="text-lg font-medium">Daftar Booking</h2>
    @include('partials.booking-table', ['bookings' => $bookings])
</div>

<div class="flex flex-col gap-4 bg-white rounded-lg p-4 md:p-6 shadow-main">
    <h2 class="text-lg font-medium">Daftar Pembayaran</h2>
    @include('partials.payment-details-table', ['paymentDetails' => $paymentDetails])
</div>
