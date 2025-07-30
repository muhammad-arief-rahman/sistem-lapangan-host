@if ($href)
    <a href="{{ $href }}"
        class="flex flex-col gap-2 shadow-main bg-white rounded-lg p-4 md:p-6 hover:bg-zinc-50 transition-all group hover:scale-[.99]">
        <div class="flex items-center gap-4 justify-between">
            <h2 class="text-zinc-500 md:text-base text-sm group-hover:text-primary flex items-center">
                <span>{{ $title }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square ml-2 text-xs"></i>
            </h2>
            <div class="grid place-items-center size-6">
                <div class="md:text-lg text-zinc-500">
                    {{ $icon }}
                </div>
            </div>
        </div>
        <span class="md:text-2xl text-xl font-semibold text-primary">
            {{ $value }}
        </span>
    </a>
@else
    <div class="flex flex-col gap-2 shadow-main bg-white rounded-lg p-4 md:p-6">
        <div class="flex items-center gap-4 justify-between">
            <h2 class="text-zinc-500 md:text-base text-sm">
                {{ $title }}
            </h2>
            <div class="grid place-items-center size-6">
                <div class="md:text-lg text-zinc-500">
                    {{ $icon }}
                </div>
            </div>
        </div>
        <span class="md:text-2xl text-xl font-semibold text-primary">
            {{ $value }}
        </span>
    </div>
@endif
