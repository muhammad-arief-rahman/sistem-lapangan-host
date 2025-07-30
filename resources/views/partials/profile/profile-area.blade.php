<div class="flex flex-col gap-4">
    <div class="bg-white p-6 rounded-xl shadow-main relative z-10 peer">
        <div class="flex gap-4">
            <img src="{{ $user->getImageUrl() }}" alt="Profile" class="size-24 rounded-full object-cover bg-zinc-200">
            <div class="flex flex-col flex-1 justify-center">
                <h3 class="text-xl font-semibold">
                    {{ $user->name }}
                </h3>
                <p class="text-zinc-700 text-sm">{{ $user->getRoleName() }}</p>
                <div class="mt-1 flex items-center gap-2">
                    <div class="size-4 text-zinc-500">
                        <x-icons.mail />
                    </div>
                    <p class="text-zinc-700 ">{{ $user->email }}</p>
                </div>
                <div class="mt-1 flex items-center gap-2">
                    <div class="size-4 text-zinc-500">
                        <x-icons.phone />
                    </div>
                    <p class="text-zinc-700 ">{{ $user->phone }}</p>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="ml-auto ">
        <button data-toggle='edit-profile'
            class="mr-2 px-4 py-2 text-sm bg-primary cursor-pointer text-white rounded-md hover:bg-primary/90 duration-200 font-medium">
            <span id="edit-profile-text">Edit Profil</span>
        </button>
    </div> --}}
</div>


