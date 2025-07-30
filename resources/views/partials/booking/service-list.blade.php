<div class="flex flex-col gap-6">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="form-control">
            <h2 class="text-lg font-semibold text-primary">Pilih Fotografer</h2>

            <fieldset class="flex flex-col gap-4 max-h-[240px] overflow-y-auto">
                @forelse ($services['photographer'] ?? [] as $service)
                    <x-service-list-item id="photographer" name="photographer_id" :service="$service" />
                @empty
                    <div class="col-span-2">
                        <p class="text-sm text-zinc-500">Tidak ada fotografer yang tersedia.</p>
                    </div>
                @endforelse
            </fieldset>
        </div>

        <div class="form-control">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold text-primary">Pilih Wasit</h2>
            </div>
            <fieldset class="flex flex-col gap-4 max-h-[240px] overflow-y-auto">
                @forelse ($services['referee'] ?? [] as $service)
                    <x-service-list-item id="referee" name="referee_id" :service="$service" />
                @empty
                    <div class="col-span-2">
                        <p class="text-sm text-zinc-500">Tidak ada wasit yang tersedia.</p>
                    </div>
                @endforelse
            </fieldset>
        </div>
    </div>
</div>
