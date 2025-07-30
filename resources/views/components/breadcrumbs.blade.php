@props([
    'breadcrumbs' => [],
])

@php
    $baseBreadcrumbs = [
        'Dashboard' => route('dashboard.index'),
    ];

    // Check if breadcrumbs is a nested array or a simple associative array
    if (is_array($breadcrumbs) && isset($breadcrumbs[0]) && is_array($breadcrumbs[0])) {
        // Flatten the nested array
        $breadcrumbs = array_reduce(
            $breadcrumbs,
            function ($carry, $item) {
                return array_merge($carry, $item);
            },
            [],
        );
    }

    $newBreadcrumbs = array_merge($baseBreadcrumbs, $breadcrumbs);

@endphp


<div class="flex">
    <nav class="flex items-center gap-2 text-sm text-gray-500 ">
        @foreach ($newBreadcrumbs as $label => $url)
            @php
                $isSecondToLast = count($newBreadcrumbs) > 1 ? $loop->remaining === 1 : $loop->first;
            @endphp

            @if (count($newBreadcrumbs) > 1 && $isSecondToLast)
                <span class="text-gray-400 md:hidden inline-block">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @endif
            <a href="{{ $url }}"
                class="hover:text-primary {{ !$isSecondToLast ? 'hidden md:inline-block' : '' }}">
                {{ $label }}
            </a>
            @if (!$loop->last)
                <span class="text-gray-400 hidden md:inline-block">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif
        @endforeach
    </nav>
</div>
