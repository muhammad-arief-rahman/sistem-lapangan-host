@php
    $user = auth()->user();
@endphp

<div class="relative">
    <div class="avatar-profile-trigger">
        {{ $trigger }}
    </div>

    <div class="avatar-profile-content duration-200 transition-all z-[100] relative"
        style="display: none; opacity: 0; translate: 0 -16px;" aria-expanded="false">
        <div class="absolute right-0 w-xs bg-white shadow-main rounded-lg p-4 z-[100]">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ 'https://placehold.co/150' }}" alt="avatar" class="size-16 rounded-full">
                    <div>
                        <h4 class="text-lg font-semibold text-zinc-800">{{ $user->name }}</h4>
                        <p class="text-sm text-zinc-500">
                            {{ $user->getRoleName() }}
                        </p>
                        <div class="text-sm text-zinc-500 flex items-center gap-2 mt-2">
                            <span class="text-sm text-primary">
                                <i class="fa-solid fa-wallet"></i>
                            </span>
                            <span class="text-zinc-700 font-medium">{{ format_rp($user->balance) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('logout') }}" class="contents">
                        <button type="submit" class="btn btn-primary w-full">
                            Logout
                        </button>
                    </form>
                    <a href="{{ route('dashboard.index') }}" class="contents">
                        <button type="button" class="btn btn-primary w-full">
                            Dashboard
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.avatar-profile-trigger').on('click', function() {
            if ($('.avatar-profile-content').attr('aria-expanded') === 'false') {
                $('.avatar-profile-content').show().attr('aria-expanded', 'true').css({
                    opacity: 1,
                    translate: '0 0'
                });
            } else {
                $('.avatar-profile-content').hide().attr('aria-expanded', 'false').css({
                    opacity: 0,
                    translate: '0 -16px'
                });
            }
        });

        $(document).on('click', function(event) {
            // Ignore if the event target is the content
            if ($(event.target).closest('.avatar-profile-content').length) {
                return;
            }

            if (!$(event.target).closest('.avatar-profile-trigger').length) {
                setTimeout(function() {
                    $('.avatar-profile-content').hide();
                }, 100);
            }
        });
    })
</script>
