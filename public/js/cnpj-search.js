document.addEventListener('DOMContentLoaded', function() {
    const cnpjInput = document.getElementById('cnpj');
    const searchButton = document.getElementById('search-cnpj');
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorMessage = document.getElementById('error-message');

    if (cnpjInput && searchButton) {
        // Máscara para CNPJ
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });

        // Busca CNPJ
        searchButton.addEventListener('click', async function() {
            const cnpj = cnpjInput.value.replace(/\D/g, '');
            
            if (cnpj.length !== 14) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: 'CNPJ inválido. Digite um CNPJ com 14 dígitos.',
                        type: 'error'
                    }
                }));
                return;
            }

            try {
                loadingIndicator.classList.remove('hidden');
                searchButton.disabled = true;
                errorMessage.classList.add('hidden');

                console.log('CNPJ:', cnpj); // Log para verificar o CNPJ formatado

                const response = await fetch(`https://brasilapi.com.br/api/cnpj/v1/${cnpj}`);
                console.log('Response Status:', response.status); // Log para verificar o status da resposta

                const data = await response.json();
                console.log('API Response:', data); // Log para verificar a resposta da API

                if (!response.ok) {
                    console.error('Erro na API:', data.message); // Log para erros da API
                    throw new Error(data.message || 'Erro ao buscar CNPJ');
                }

                // Atualiza os campos do formulário
                document.getElementById('company_name').value = data.razao_social || '';
                document.getElementById('trading_name').value = data.nome_fantasia || '';
                document.getElementById('address').value = [
                    data.logradouro,
                    data.numero,
                    data.complemento,
                    data.bairro
                ].filter(Boolean).join(', ') || '';
                document.getElementById('city').value = data.municipio || '';
                document.getElementById('state').value = data.uf || '';
                document.getElementById('zip_code').value = data.cep || '';
                
                // Dispara eventos para atualizar o Livewire
                ['company_name', 'trading_name', 'address', 'city', 'state', 'zip_code'].forEach(field => {
                    const element = document.getElementById(field);
                    element.dispatchEvent(new Event('input', { bubbles: true }));
                });

                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: 'Dados do CNPJ carregados com sucesso!',
                        type: 'success'
                    }
                }));

            } catch (error) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: error.message || 'Erro ao buscar CNPJ',
                        type: 'error'
                    }
                }));
            } finally {
                loadingIndicator.classList.add('hidden');
                searchButton.disabled = false;
            }
        });
    }
});
