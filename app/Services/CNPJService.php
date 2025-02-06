<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CNPJService
{
    protected string $baseUrl;
    protected int $cacheMinutes = 60 * 24; // 24 horas

    public function __construct()
    {
        $this->baseUrl = 'https://brasilapi.com.br/api/cnpj/v1';
    }

    public function fetch(string $cnpj): array
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        return Cache::remember("cnpj.{$cnpj}", $this->cacheMinutes, function () use ($cnpj) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get("{$this->baseUrl}/{$cnpj}");

            if (!$response->successful()) {
                throw new \Exception('Não foi possível consultar o CNPJ');
            }

            $data = $response->json();

            return [
                'cnpj' => $cnpj,
                'company_name' => $data['razao_social'] ?? null,
                'trading_name' => $data['nome_fantasia'] ?? null,
                'address' => $this->formatAddress($data),
                'city' => $data['municipio'] ?? null,
                'state' => $data['uf'] ?? null,
                'zip_code' => preg_replace('/[^0-9]/', '', $data['cep'] ?? ''),
                'phone' => $this->formatPhone($data['ddd_telefone_1'] ?? null),
                'email' => $data['email'] ?? null,
            ];
        });
    }

    protected function formatAddress(array $data): string
    {
        $parts = array_filter([
            $data['logradouro'] ?? null,
            $data['numero'] ?? null,
            $data['complemento'] ?? null,
            $data['bairro'] ?? null,
        ]);

        return implode(', ', $parts);
    }

    protected function formatPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $phone);
    }
}
