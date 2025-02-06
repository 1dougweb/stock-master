<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use App\Http\Livewire\SupplierList;
use App\Http\Livewire\SupplierForm;
use App\Http\Livewire\MaterialRequestList;
use App\Http\Livewire\ProductForm;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('supplier-list', SupplierList::class);
        Livewire::component('supplier-form', SupplierForm::class);
        Livewire::component('material-request-list', MaterialRequestList::class);
        Livewire::component('product-form', ProductForm::class);

        // Validação de CNPJ
        Validator::extend('formato_cnpj', function ($attribute, $value, $parameters, $validator) {
            // Remove caracteres não numéricos
            $value = preg_replace('/[^0-9]/', '', $value);
            
            // Verifica se tem 14 dígitos
            if (strlen($value) != 14) {
                return false;
            }

            // Verifica se todos os dígitos são iguais
            if (preg_match('/^(\d)\1*$/', $value)) {
                return false;
            }

            // Calcula o primeiro dígito verificador
            $soma = 0;
            $mult = 5;
            for ($i = 0; $i < 12; $i++) {
                $soma += $value[$i] * $mult;
                $mult = ($mult == 2) ? 9 : $mult - 1;
            }
            $digito1 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

            // Calcula o segundo dígito verificador
            $soma = 0;
            $mult = 6;
            for ($i = 0; $i < 13; $i++) {
                $soma += $value[$i] * $mult;
                $mult = ($mult == 2) ? 9 : $mult - 1;
            }
            $digito2 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

            // Verifica se os dígitos calculados são iguais aos dígitos informados
            return ($value[12] == $digito1 && $value[13] == $digito2);
        });

        // Validação de CEP
        Validator::extend('formato_cep', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\d{5}-?\d{3}$/', $value);
        });

        // Validação de Celular com DDD
        Validator::extend('celular_com_ddd', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $value);
        });

        // Mensagens de erro personalizadas
        Validator::replacer('formato_cnpj', function ($message, $attribute, $rule, $parameters) {
            return 'O CNPJ informado não é válido.';
        });

        Validator::replacer('formato_cep', function ($message, $attribute, $rule, $parameters) {
            return 'O CEP deve estar no formato 00000-000.';
        });

        Validator::replacer('celular_com_ddd', function ($message, $attribute, $rule, $parameters) {
            return 'O telefone deve estar no formato (00) 00000-0000.';
        });
    }
}
