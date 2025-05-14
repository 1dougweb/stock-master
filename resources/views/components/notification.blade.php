@props([
    'type' => 'success', 
    'message' => ''
])

@php
    $classes = match ($type) {
        'success' => 'bg-green-500 text-white',
        'error' => 'bg-red-500 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'info' => 'bg-blue-500 text-white',
        default => 'bg-green-500 text-white',
    };
@endphp

<div class="fixed bottom-0 right-0 m-6 z-50 notification-component">
    <div class="{{ $classes }} px-6 py-4 rounded-lg shadow-lg">
        {{ $message ?? $slot }}
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.notification-component');
            if (notification) {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }
        }, 6000);
    </script>
</div> 