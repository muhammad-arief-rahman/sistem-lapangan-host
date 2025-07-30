@props([
    'route' => '',
])

<a href="{{ route($route) }}"
    class="min-h-12 py-2 flex items-center gap-3 px-4 rounded-md hover:brightness-90 duration-100 {{ isActive($route) }}">
    <div class="size-6 shrink-0 grid place-items-center">
        {{ $icon }}
    </div>
    <span>
        {{ $slot }}
    </span>
</a>
