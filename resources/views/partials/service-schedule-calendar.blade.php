<div class="md:p-8 p-4 bg-white md:rounded-2xl rounded-md xl:h-[calc(100vh)] h-[calc(50dvh)] flex flex-col gap-4">
    <div class="form-control">
        <h3 class="text-lg font-semibold">Keterangan</h3>
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <div class="size-4 bg-primary"></div>
                <span>Jadwal Aktif</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="size-4 bg-zinc-300"></div>
                <span>Jadwal Tidak Aktif</span>
            </div>
        </div>
    </div>
    <div id="calendar" class="grow {{ auth()->user()->role !== 'super_admin' ? 'clickable-calendar' : '' }}">
    </div>
</div>


