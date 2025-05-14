@props(['message'])

<div class="fixed bottom-0 right-0 m-6 z-50 notification-component success-notification">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ $message }}</span>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.success-notification');
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