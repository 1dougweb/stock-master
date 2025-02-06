<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Collection $products;

    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Alerta de Estoque Baixo')
            ->greeting('Olá!')
            ->line('Os seguintes produtos estão com estoque baixo:')
            ->line('');

        foreach ($this->products as $product) {
            $message->line("- {$product->name}: {$product->stock} unidades (Mínimo: {$product->min_stock})");
        }

        return $message
            ->action('Ver Produtos', url('/products'))
            ->line('Obrigado por usar nosso sistema!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Existem ' . $this->products->count() . ' produtos com estoque baixo.',
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                ];
            })->toArray(),
        ];
    }
}
