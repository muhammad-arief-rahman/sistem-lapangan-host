<div>
    <input type="radio" name="{{ $name }}" id="service-{{ $service->id }}" value="{{ $service->id }}"
        class="hidden peer  {{ $id }}-radio" data-service-price="{{ $service->price_per_hour }}">
    <label for="service-{{ $service->id }}" id="{{ $id }}-{{ $service->id }}-label"
        class="h-24 {{ $id }}-radio-label  border border-zinc-200 flex justify-between gap-2 rounded-lg p-4 cursor-pointer group peer-not-checked:hover:bg-zinc-50 duration-100 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary">
        <div class="flex items-center gap-2">
            <img src="{{ $service->user->getImageUrl() }}" alt="Service {{ $service->user->name }}"
                class="aspect-square rounded-full h-full bg-zinc-200">
            <div class="flex flex-col">
                <h3 class="text-lg font-semibold" id="photograher-{{ $service->id }}-name">
                    {{ $service->user->name }}
                </h3>

                <div class="text-primary text-sm font-medium group-[&:is(:where(.peer):checked_~_*)]:text-white">
                    {{ format_rp($service->price_per_hour) }} <span class="text-xs">/ jam</span>
                </div>

                <div class="text-xs text-zinc-600 group-[&:is(:where(.peer):checked_~_*)]:text-white mt-1">
                    <span>Bergabung sejak:</span>
                    <span>{{ $service->user->created_at->translatedFormat('j F Y') }}</span>
                </div>

                {{-- <p class="text-sm text-zinc-600 group-[&:is(:where(.peer):checked_~_*)]:text-white">
                    {{ $service->description }}</p> --}}
            </div>
        </div>
        <a href="{{ route('service.show', $service->id) }}" class="contents service-show-btn" target="_blank">
            <button type="button" title="Lihat Profil Jasa"
                class="bg-white cursor-pointer text-primary border border-primary h-8 w-8 flex justify-center self-center gap-1 items-center place-items-center text-sm rounded-full hover:bg-primary hover:text-white duration-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-square-arrow-out-up-right-icon lucide-square-arrow-out-up-right size-4">
                    <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" />
                    <path d="m21 3-9 9" />
                    <path d="M15 3h6v6" />
                </svg>
            </button>
        </a>
    </label>
</div>

<script>
    (() => {
        const label = document.querySelector('#{{ $id }}-{{ $service->id }}-label');

        label.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();

            const inputId = label.getAttribute('for');
            const radio = document.getElementById(inputId);

            if (radio && radio.checked) {
                e.preventDefault();

                setTimeout(() => {
                    radio.checked = false;
                }, 100);
            } else {
                radio.checked = true;
            }
        })

        document.querySelectorAll('.service-show-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    })()
</script>
