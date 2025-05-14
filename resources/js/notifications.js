/**
 * Gerenciador de notificações para exibir mensagens de feedback ao usuário
 */
class NotificationManager {
    constructor() {
        this.container = this.createContainer();
        document.body.appendChild(this.container);
        this.setupLivewireListeners();
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'notifications-container';
        container.className = 'fixed bottom-0 right-0 m-6 z-50 space-y-3';
        return container;
    }

    setupLivewireListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Livewire) {
                // Livewire 3.x events
                document.addEventListener('livewire:initialized', () => {
                    this.listenToLivewireEvents();
                });
            }
        });
    }

    listenToLivewireEvents() {
        Livewire.on('notification', (data) => {
            this.show(data.message, data.type || 'info');
        });
    }

    /**
     * Exibe uma notificação
     * @param {string} message - A mensagem a ser exibida
     * @param {string} type - O tipo de notificação (success, error, warning, info)
     * @param {number} duration - Duração em milissegundos
     */
    show(message, type = 'success', duration = 6000) {
        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);

        // Remover após a duração especificada
        setTimeout(() => {
            notification.classList.add('opacity-0');
            setTimeout(() => {
                if (notification.parentNode === this.container) {
                    this.container.removeChild(notification);
                }
            }, 500);
        }, duration);
    }

    createNotification(message, type) {
        const notification = document.createElement('div');
        const bgColor = this.getBackgroundColor(type);
        
        notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-500`;
        notification.textContent = message;
        
        return notification;
    }

    getBackgroundColor(type) {
        switch (type) {
            case 'success': return 'bg-green-500';
            case 'error': return 'bg-red-500';
            case 'warning': return 'bg-yellow-500';
            case 'info': return 'bg-blue-500';
            default: return 'bg-green-500';
        }
    }
}

// Inicializar gerenciador de notificações
window.notificationManager = new NotificationManager();

// Inicializar notificações da sessão flash
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se há mensagens flash para exibir
    const successEl = document.querySelector('meta[name="notification-success"]');
    const errorEl = document.querySelector('meta[name="notification-error"]');
    const infoEl = document.querySelector('meta[name="notification-info"]');
    
    if (successEl && successEl.content) {
        window.notificationManager.show(successEl.content, 'success');
    }
    
    if (errorEl && errorEl.content) {
        window.notificationManager.show(errorEl.content, 'error');
    }
    
    if (infoEl && infoEl.content) {
        window.notificationManager.show(infoEl.content, 'info');
    }
}); 