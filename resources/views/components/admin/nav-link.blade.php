
@props([
    'href' => '#',
    'icon' => '',
    'label' => '',
    'active' => false,
    'badgeCount' => null,
    'submenu' => false,
    'disabled' => false
])

@php
    $baseClasses = 'group relative flex items-center justify-center rounded-lg transition-colors duration-200 ease-in-out';
    $activeClasses = $active 
        ? 'bg-blue-50 text-blue-700' 
        : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
    $disabledClasses = $disabled 
        ? 'opacity-50 cursor-not-allowed' 
        : 'cursor-pointer';
    $classes = $baseClasses . ' ' . $activeClasses . ' ' . $disabledClasses;
@endphp

<a href="{{ $disabled ? '#' : $href }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) 
        onclick="event.preventDefault()" 
    @endif
>
    {{-- Icon Container --}}
    <div class="relative flex h-10 w-10 items-center justify-center">
        {{-- Material Icon --}}
        <span class="material-icons {{ $active ? 'text-blue-700' : 'text-gray-600 group-hover:text-gray-900' }}">
            {{ $icon }}
        </span>

        {{-- Badge (if provided) --}}
        @if($badgeCount !== null)
            <span class="absolute -top-1 -right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-medium text-white">
                {{ $badgeCount }}
            </span>
        @endif
    </div>

    {{-- Label Tooltip --}}
    <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-md bg-gray-900 px-2 py-1.5 text-xs font-medium text-center text-white group-hover:visible">
        {{ $label }}
        @if($disabled)
            (Disabled)
        @endif
    </span>

    {{-- Submenu Indicator --}}
    @if($submenu)
        <span class="material-icons absolute right-0 text-sm {{ $active ? 'text-blue-700' : 'text-gray-400' }}">
            chevron_right
        </span>
    @endif
</a>