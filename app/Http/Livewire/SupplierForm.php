<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;

class SupplierForm extends Component
{
    public $supplier;
    public $cnpj;
    public $company_name;
    public $trading_name;
    public $address;
    public $city;
    public $state;
    public $zip_code;
    public $phone;
    public $whatsapp;
    public $email;
    public $contact_person;

    protected $rules = [
        'cnpj' => 'required|string|min:14|max:18',
        'company_name' => 'required|string|max:255',
        'trading_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|size:2',
        'zip_code' => 'required|string|min:8|max:9',
        'phone' => 'required|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
        'email' => 'required|email|max:255',
        'contact_person' => 'required|string|max:255',
    ];

    protected $messages = [
        'cnpj.required' => 'O CNPJ é obrigatório',
        'cnpj.min' => 'O CNPJ deve ter no mínimo 14 dígitos',
        'cnpj.max' => 'O CNPJ deve ter no máximo 18 caracteres',
        'company_name.required' => 'A razão social é obrigatória',
        'company_name.max' => 'A razão social deve ter no máximo 255 caracteres',
        'trading_name.required' => 'O nome fantasia é obrigatório',
        'trading_name.max' => 'O nome fantasia deve ter no máximo 255 caracteres',
        'address.required' => 'O endereço é obrigatório',
        'address.max' => 'O endereço deve ter no máximo 255 caracteres',
        'city.required' => 'A cidade é obrigatória',
        'city.max' => 'A cidade deve ter no máximo 100 caracteres',
        'state.required' => 'O estado é obrigatório',
        'state.size' => 'O estado deve ter 2 caracteres',
        'zip_code.required' => 'O CEP é obrigatório',
        'zip_code.min' => 'O CEP deve ter no mínimo 8 dígitos',
        'zip_code.max' => 'O CEP deve ter no máximo 9 caracteres',
        'phone.required' => 'O telefone é obrigatório',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres',
        'whatsapp.max' => 'O WhatsApp deve ter no máximo 20 caracteres',
        'email.required' => 'O e-mail é obrigatório',
        'email.email' => 'Digite um e-mail válido',
        'email.max' => 'O e-mail deve ter no máximo 255 caracteres',
        'contact_person.required' => 'A pessoa de contato é obrigatória',
        'contact_person.max' => 'A pessoa de contato deve ter no máximo 255 caracteres',
    ];

    public function mount($supplier = null)
    {
        if ($supplier) {
            $this->supplier = $supplier;
            $this->cnpj = $supplier->cnpj;
            $this->company_name = $supplier->company_name;
            $this->trading_name = $supplier->trading_name;
            $this->address = $supplier->address;
            $this->city = $supplier->city;
            $this->state = $supplier->state;
            $this->zip_code = $supplier->zip_code;
            $this->phone = $supplier->phone;
            $this->whatsapp = $supplier->whatsapp;
            $this->email = $supplier->email;
            $this->contact_person = $supplier->contact_person;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'cnpj' => $this->cnpj,
                'company_name' => $this->company_name,
                'trading_name' => $this->trading_name,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'contact_person' => $this->contact_person,
            ];

            if ($this->supplier) {
                $this->supplier->update($data);
                $message = 'Fornecedor atualizado com sucesso!';
            } else {
                Supplier::create($data);
                $message = 'Fornecedor cadastrado com sucesso!';
            }

            // Usar session flash para a mensagem
            session()->flash('success', $message);
            
            // Despachar evento para notificação no frontend (mantendo para compatibilidade)
            $this->dispatch('supplier-saved', [
                'message' => $message,
                'type' => 'success'
            ]);
            
            // Redirecionar para a lista de fornecedores
            return redirect()->route('suppliers.index');
        } catch (\Exception $e) {
            // Usar session flash para o erro
            session()->flash('error', 'Erro ao salvar o fornecedor: ' . $e->getMessage());
            
            // Despachar evento de erro para notificação no frontend
            $this->dispatch('supplier-error', [
                'message' => 'Erro ao salvar o fornecedor: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function fetchCNPJ($cnpj)
    {
        try {
            $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
            
            if (strlen($cnpj) !== 14) {
                return $this->dispatch('cnpj-error', ['message' => 'CNPJ deve ter 14 dígitos']);
            }
            
            // Log para depuração
            \Log::info("Consultando CNPJ: {$cnpj}");
            
            // Mostrar mensagem de carregamento
            $this->dispatch('notification', [
                'message' => 'Consultando CNPJ...',
                'type' => 'info'
            ]);
            
            // Configurar tempo limite para todas as APIs - máximo 10 segundos
            // Isto evita que o usuário fique esperando indefinidamente
            $timeout = 10;
            
            // Tentar com a primeira API (BrasilAPI)
            $success = $this->tryBrasilAPI($cnpj, $timeout / 3);
            
            // Se falhou com a primeira API, tenta com a segunda (ReceitaWS)
            if (!$success) {
                $success = $this->tryReceitaWS($cnpj, $timeout / 3);
            }
            
            // Se ainda não conseguiu, tenta com a terceira (CNPJ.ws)
            if (!$success) {
                $success = $this->tryCNPJWS($cnpj, $timeout / 3);
            }
            
            // Se ainda não conseguiu, tenta com a API específica para nome fantasia
            if (!$success || empty($this->trading_name) || $this->trading_name === $this->company_name) {
                $success = $this->trySpecialNomeFantasiaAPI($cnpj, $timeout / 4);
            }
            
            // Se nenhuma API funcionou
            if (!$success) {
                // Manter o CNPJ formatado no campo
                $formattedCnpj = $this->formatCNPJ($cnpj);
                $this->cnpj = $formattedCnpj;
                
                // Notificar o usuário que não foi possível obter os dados, mas que ele pode continuar
                $this->dispatch('cnpj-not-found', [
                    'message' => 'Não foi possível obter os dados deste CNPJ em nenhuma das APIs disponíveis. Por favor, preencha os campos manualmente.',
                    'cnpj' => $formattedCnpj
                ]);
                
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Exceção ao consultar CNPJ: " . $e->getMessage());
            return $this->dispatch('cnpj-error', [
                'message' => 'Erro ao consultar CNPJ: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Tenta buscar o CNPJ usando a BrasilAPI
     * @param string $cnpj
     * @param int $timeout Tempo limite em segundos
     * @return bool
     */
    private function tryBrasilAPI($cnpj, $timeout = 5)
    {
        try {
            \Log::info("Tentando BrasilAPI para CNPJ: {$cnpj} com timeout de {$timeout}s");
            
            $response = Http::timeout($timeout)->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
            
            if (!$response->successful()) {
                \Log::warning("BrasilAPI - Erro na API: Status " . $response->status());
                return false;
            }
            
            $data = $response->json();
            
            if (isset($data['message']) && str_contains(strtolower($data['message']), 'error')) {
                \Log::warning("BrasilAPI - Erro nos dados: " . $data['message']);
                return false;
            }
            
            \Log::info("BrasilAPI - Dados obtidos com sucesso");
            
            // Mapeando campos da BrasilAPI
            $this->company_name = $data['razao_social'] ?? '';
            
            // Fix para nome fantasia - BrasilAPI
            if (!empty($data['nome_fantasia'])) {
                $this->trading_name = $data['nome_fantasia'];
            } else {
                // Se o nome fantasia estiver vazio, usa a razão social como fallback
                $this->trading_name = $data['razao_social'] ?? '';
            }
            
            \Log::info("BrasilAPI - Nome Fantasia: " . $this->trading_name);
            
            $this->email = $data['email'] ?? '';
            
            // Formatação do telefone
            $phone = $data['ddd_telefone_1'] ?? $data['ddd_telefone_2'] ?? '';
            $this->formatAndSetPhone($phone);
            
            // Formatação do CEP
            $this->formatAndSetZipCode($data['cep'] ?? '');
            
            // Formatação do endereço
            $address = $data['logradouro'] ?? '';
            if (!empty($data['numero'])) {
                $address .= ', ' . $data['numero'];
            }
            if (!empty($data['complemento'])) {
                $address .= ', ' . $data['complemento'];
            }
            if (!empty($data['bairro'])) {
                $address .= ', ' . $data['bairro'];
            }
            $this->address = $address;
            
            $this->city = $data['municipio'] ?? '';
            $this->state = $data['uf'] ?? '';
            
            $this->updateFormFields();
            return true;
        } catch (\Exception $e) {
            \Log::error("BrasilAPI - Exceção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tenta buscar o CNPJ usando a ReceitaWS
     * @param string $cnpj
     * @param int $timeout Tempo limite em segundos
     * @return bool
     */
    private function tryReceitaWS($cnpj, $timeout = 5)
    {
        try {
            \Log::info("Tentando ReceitaWS para CNPJ: {$cnpj} com timeout de {$timeout}s");
            
            $response = Http::timeout($timeout)->get("https://receitaws.com.br/v1/cnpj/{$cnpj}");
            
            if (!$response->successful()) {
                \Log::warning("ReceitaWS - Erro na API: Status " . $response->status());
                return false;
            }
            
            $data = $response->json();
            
            if (isset($data['status']) && $data['status'] === 'ERROR') {
                \Log::warning("ReceitaWS - Erro nos dados: " . ($data['message'] ?? 'Erro desconhecido'));
                return false;
            }
            
            \Log::info("ReceitaWS - Dados obtidos com sucesso");
            
            // Mapeando campos da ReceitaWS
            $this->company_name = $data['nome'] ?? '';
            
            // Fix para nome fantasia - ReceitaWS
            if (!empty($data['fantasia']) && $data['fantasia'] != "********") {
                $this->trading_name = $data['fantasia'];
            } else {
                // ReceitaWS às vezes retorna asteriscos, usar razão social como fallback
                $this->trading_name = $data['nome'] ?? '';
            }
            
            \Log::info("ReceitaWS - Nome Fantasia: " . $this->trading_name);
            
            $this->email = $data['email'] ?? '';
            
            // Formatação do telefone
            $this->formatAndSetPhone($data['telefone'] ?? '');
            
            // Formatação do CEP
            $this->formatAndSetZipCode($data['cep'] ?? '');
            
            // Formatação do endereço
            $address = $data['logradouro'] ?? '';
            if (!empty($data['numero'])) {
                $address .= ', ' . $data['numero'];
            }
            if (!empty($data['complemento'])) {
                $address .= ', ' . $data['complemento'];
            }
            if (!empty($data['bairro'])) {
                $address .= ', ' . $data['bairro'];
            }
            $this->address = $address;
            
            $this->city = $data['municipio'] ?? '';
            $this->state = $data['uf'] ?? '';
            
            $this->updateFormFields();
            return true;
        } catch (\Exception $e) {
            \Log::error("ReceitaWS - Exceção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tenta buscar o CNPJ usando a CNPJ.ws
     * @param string $cnpj
     * @param int $timeout Tempo limite em segundos
     * @return bool
     */
    private function tryCNPJWS($cnpj, $timeout = 5)
    {
        try {
            \Log::info("Tentando CNPJ.ws para CNPJ: {$cnpj} com timeout de {$timeout}s");
            
            // Esta API pode precisar de chave de API em ambiente de produção
            $response = Http::timeout($timeout)->get("https://publica.cnpj.ws/cnpj/{$cnpj}");
            
            if (!$response->successful()) {
                \Log::warning("CNPJ.ws - Erro na API: Status " . $response->status());
                return false;
            }
            
            $data = $response->json();
            
            \Log::info("CNPJ.ws - Dados obtidos com sucesso");
            
            // Mapeando campos do CNPJ.ws (estrutura diferente)
            $this->company_name = $data['razao_social'] ?? '';
            
            // Fix para nome fantasia - CNPJ.ws
            if (!empty($data['nome_fantasia'])) {
                $this->trading_name = $data['nome_fantasia'];
            } else {
                // Se o nome fantasia estiver vazio, tenta pegar do estabelecimento
                $this->trading_name = $data['estabelecimento']['nome_fantasia'] ?? $data['razao_social'] ?? '';
            }
            
            \Log::info("CNPJ.ws - Nome Fantasia: " . $this->trading_name);
            
            $this->email = '';
            
            // Endereço (estrutura diferente)
            if (isset($data['estabelecimento']) && isset($data['estabelecimento']['tipo_logradouro'])) {
                $address = ($data['estabelecimento']['tipo_logradouro'] ?? '') . ' ' . 
                           ($data['estabelecimento']['logradouro'] ?? '');
                           
                if (!empty($data['estabelecimento']['numero'])) {
                    $address .= ', ' . $data['estabelecimento']['numero'];
                }
                
                if (!empty($data['estabelecimento']['complemento'])) {
                    $address .= ', ' . $data['estabelecimento']['complemento'];
                }
                
                if (!empty($data['estabelecimento']['bairro'])) {
                    $address .= ', ' . $data['estabelecimento']['bairro'];
                }
                
                $this->address = $address;
                $this->city = $data['estabelecimento']['cidade']['nome'] ?? '';
                $this->state = $data['estabelecimento']['estado']['sigla'] ?? '';
                $this->formatAndSetZipCode($data['estabelecimento']['cep'] ?? '');
            }
            
            $this->updateFormFields();
            return true;
        } catch (\Exception $e) {
            \Log::error("CNPJ.ws - Exceção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tenta obter especificamente o nome fantasia de uma API especializada
     * @param string $cnpj
     * @param int $timeout Tempo limite em segundos
     * @return bool
     */
    private function trySpecialNomeFantasiaAPI($cnpj, $timeout = 3)
    {
        try {
            \Log::info("Tentando API especial para Nome Fantasia do CNPJ: {$cnpj} com timeout de {$timeout}s");
            
            // Tentativa com a CadastroCNPJ.com API
            $response = Http::timeout($timeout)->get("https://api.casadosdados.com.br/v2/public/cnpj/{$cnpj}");
            
            if (!$response->successful()) {
                \Log::warning("API Nome Fantasia - Erro na API: Status " . $response->status());
                return false;
            }
            
            $data = $response->json();
            
            if (empty($data) || isset($data['error'])) {
                \Log::warning("API Nome Fantasia - Erro nos dados");
                return false;
            }
            
            \Log::info("API Nome Fantasia - Dados obtidos com sucesso");
            
            // Se encontrou dados e o nome fantasia não está vazio, atualiza
            if (isset($data['result']['nome_fantasia']) && !empty($data['result']['nome_fantasia'])) {
                $this->trading_name = $data['result']['nome_fantasia'];
                \Log::info("API Nome Fantasia - Nome Fantasia atualizado para: " . $this->trading_name);
                
                // Atualiza apenas o campo de nome fantasia sem mexer nos outros
                $this->dispatch('update-field', [
                    'field' => 'trading_name',
                    'value' => $this->trading_name
                ]);
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error("API Nome Fantasia - Exceção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Formata e define o telefone
     * @param string $phone
     */
    private function formatAndSetPhone($phone)
    {
        // Limpar o telefone
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 11) {
            $this->phone = '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        } elseif (strlen($phone) === 10) {
            $this->phone = '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        } else {
            $this->phone = $phone;
        }
    }
    
    /**
     * Formata e define o CEP
     * @param string $zipCode
     */
    private function formatAndSetZipCode($zipCode)
    {
        $cep = preg_replace('/[^0-9]/', '', $zipCode);
        
        if (strlen($cep) === 8) {
            $this->zip_code = substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
        } else {
            $this->zip_code = $zipCode;
        }
    }
    
    /**
     * Atualiza os campos do formulário
     */
    private function updateFormFields()
    {
        $this->dispatch('input', [
            'company_name' => $this->company_name,
            'trading_name' => $this->trading_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code
        ]);
        
        $this->dispatch('cnpj-found', [
            'message' => 'CNPJ encontrado com sucesso!'
        ]);
        
        \Log::info("Dados do CNPJ processados com sucesso");
    }

    /**
     * Formata um CNPJ para apresentação
     * @param string $cnpj
     * @return string
     */
    private function formatCNPJ($cnpj)
    {
        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }
        
        return substr($cnpj, 0, 2) . '.' . 
               substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . 
               substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
    }

    public function render()
    {
        return view('livewire.supplier-form');
    }
}
