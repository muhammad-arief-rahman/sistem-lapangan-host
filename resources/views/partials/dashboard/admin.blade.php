<div class="dashboard-cards @xl:grid-cols-5 @md:grid-cols-2 @container">
    <x-dashboard-card title="Total Pengguna" value="{{ $cardData->totalUsers }}"
        href="{{ route('dashboard.user.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-users"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Lapangan" value="{{ $cardData->totalFields }}" href="{{ route('dashboard.field.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-flag"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Fotografer" value="{{ $cardData->totalPhotographers }}">
        <x-slot:icon>
            <i class="fa-solid fa-camera"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Wasit" value="{{ $cardData->totalReferees }}">
        <x-slot:icon>
            <i class="fa-solid fa-camera"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <div class="cols-span-2 @xl:col-span-1">
        <x-dashboard-card title="Pengelola Lapangan" value="{{ $cardData->totalManagers }}">
            <x-slot:icon>
                <i class="fa-solid fa-flag"></i>
            </x-slot:icon>
        </x-dashboard-card>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="col-span-3 bg-white shadow-main rounded-lg p-4 md:p-6 flex flex-col gap-4">
        <h2 class="text-lg font-medium">Daftar Booking</h2>
        @include('partials.booking-table', ['bookings' => $bookings])
    </div>
</div>

<div class="border-b border-zinc-300"></div>

<div class="dashboard-cards @xl:grid-cols-5 @md:grid-cols-2 @container">
    <x-dashboard-card title="Pembayaran Menunggu (1 Hari)" value="{{ $cardData->totalPendingPayments }}"
        href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-spinner"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Total Penarikan" value="{{ $cardData->totalWithdrawals }}"
        href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-receipt"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Penarikan Menunggu" value="{{ $cardData->totalPendingWithdrawals }}"
        href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-spinner"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Total Pendapatan" value="{{ $cardData->totalIncome }}"
        href="{{ route('dashboard.mutation.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-money-bill-wave"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Total Bookingan" value="{{ $cardData->totalBookings }}"
        href="{{ route('dashboard.booking.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-calendar-check"></i>
        </x-slot:icon>
    </x-dashboard-card>
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="col-span-3 bg-white shadow-main rounded-lg p-4 md:p-6 flex flex-col gap-4">
        <h2 class="text-lg font-medium">Daftar Penarikan</h2>
        @include('partials.withdrawal-table', ['withdrawals' => $withdrawals])
    </div>
</div>
