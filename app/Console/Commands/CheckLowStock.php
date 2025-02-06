<?php

namespace App\Console\Commands;

use App\Services\StockNotificationService;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Verifica produtos com estoque baixo e notifica os administradores';

    public function handle(StockNotificationService $service): void
    {
        $this->info('Verificando produtos com estoque baixo...');
        
        try {
            $service->handle();
            $this->info('Verificação concluída com sucesso!');
        } catch (\Exception $e) {
            $this->error('Erro ao verificar estoque: ' . $e->getMessage());
        }
    }
}
