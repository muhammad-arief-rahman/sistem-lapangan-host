<div class="bg-white md:p-6 p-4 rounded-xl shadow-main relative z-10 peer">
    <div class="gap-4">
        {{-- <div class="aspect-[3/4] w-full rounded-lg bg-zinc-200 overflow-hidden col-span-1">
            <img src="{{ $service->getImageUrl() }}" alt="Profile" class="object-cover size-full">
        </div> --}}
        <div class="flex flex-col flex-1  ">
            <h3 class="text-xl font-semibold">
                {{ $service->user->getRoleName() }}
            </h3>
            <div class="text-zinc-700 text-sm">
                Masuk dari {{ $service->created_at->format('d M Y') }}
            </div>
            <p class="text-zinc-700 mt-2">
                {{ $service->description }}
            </p>
            <div class="mt-auto pt-4 pb-2 flex justify-end gap-4">
                <a href="{{ $service->portfolio }}" class="btn btn-primary btn-sm" target="_blank">
                    Lihat Portofolio
                </a>
                <div class="flex items-center gap-1">
                    <span class="text-zinc-700">Rp</span>
                    <span class="text-2xl text-primary font-semibold">
                        {{ number_format($service->price_per_hour, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-zinc-700">/Jam</span>
                </div>
            </div>
        </div>
    </div>
</div>
