@props(['message'])

<div class="fixed bottom-0 right-0 m-6 z-50 notification-component info-notification">
    <div class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ $message }}</span>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.info-notification');
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