@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'bg-green-100 dark:bg-green-900/50 border-green-400 dark:border-green-700 text-green-700 dark:text-green-300',
        'error'   => 'bg-red-100 dark:bg-red-900/50 border-red-400 dark:border-red-700 text-red-700 dark:text-red-300',
        'info'    => 'bg-blue-100 dark:bg-blue-900/50 border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-300',
    ];
    $style = $styles[$type] ?? $styles['success'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
     x-init="setTimeout(() => show = false, 5000)"
     class="flex items-center justify-between border {{ $style }} px-4 py-3 rounded mb-4" role="alert">
    <span>{{ $slot }}</span>
    <button type="button" @click="show = false" class="ml-4 font-bold opacity-60 hover:opacity-100" aria-label="Dismiss">
        <i class="fas fa-times"></i>
    </button>
</div>
