<div class="dashboard-cards">
    <x-dashboard-card title="Pendapatan" value="{{ format_rp($cardData->totalIncome) }}" href="{{ route('dashboard.mutation.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-dollar-sign"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Saldo" value="{{ format_rp($cardData->balance) }}" href="{{ route('dashboard.withdrawal.index')     }}">
        <x-slot:icon>
            <i class="fa-solid fa-wallet"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Pesanan Aktif" value="{{ $cardData->activeSchedules }}" href="{{ route('dashboard.service-schedule.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-clock"></i>
        </x-slot:icon>
    </x-dashboard-card>
    <x-dashboard-card title="Penarikan Menunggu" value="{{ $cardData->pendingWithdrawals }}" href="{{ route('dashboard.withdrawal.index') }}">
        <x-slot:icon>
            <i class="fa-solid fa-spinner"></i>
        </x-slot:icon>
    </x-dashboard-card>

</div>

<div class="flex flex-col gap-4">
    <h2 class="text-lg font-medium">Jadwal Jasa</h2>
    @include('partials.service-schedule-calendar', ['schedules' => $serviceSchedules])
    @include('partials.schedule-create-modal', ['schedules' => $serviceSchedules])
</div>
