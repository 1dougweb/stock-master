<div id="supplier-form">
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                <!-- CNPJ -->
                <div class="col-span-1">
                    <x-label for="cnpj" value="{{ __('CNPJ') }}" />
                    <div class="flex">
                        <x-input id="cnpj" type="text" class="mt-1 block w-full mask-cnpj" wire:model.defer="cnpj" />
                        <button type="button" id="searchCNPJ" class="ml-2 mt-1 inline-flex items-center px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error for="cnpj" class="mt-2" />
                </div>

                <!-- Razão Social -->
                <div class="col-span-1">
                    <x-label for="company_name" value="{{ __('Razão Social') }}" />
                    <x-input id="company_name" type="text" class="mt-1 block w-full" wire:model.defer="company_name" />
                    <x-input-error for="company_name" class="mt-2" />
                </div>

                <!-- Nome Fantasia -->
                <div class="col-span-1">
                    <x-label for="trading_name" value="{{ __('Nome Fantasia') }}" />
                    <x-input id="trading_name" type="text" class="mt-1 block w-full" wire:model.defer="trading_name" />
                    <x-input-error for="trading_name" class="mt-2" />
                </div>

                <!-- Pessoa de Contato -->
                <div class="col-span-1">
                    <x-label for="contact_person" value="{{ __('Pessoa de Contato') }}" />
                    <x-input id="contact_person" type="text" class="mt-1 block w-full" wire:model.defer="contact_person" />
                    <x-input-error for="contact_person" class="mt-2" />
                </div>

                <!-- CEP -->
                <div class="col-span-1">
                    <x-label for="zip_code" value="{{ __('CEP') }}" />
                    <div class="flex">
                        <x-input id="zip_code" type="text" class="mt-1 block w-full mask-cep" wire:model.defer="zip_code" />
                        <button type="button" id="searchCEP" class="ml-2 mt-1 inline-flex items-center px-3 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error for="zip_code" class="mt-2" />
                </div>

                <!-- Endereço -->
                <div class="col-span-1">
                    <x-label for="address" value="{{ __('Endereço') }}" />
                    <x-input id="address" type="text" class="mt-1 block w-full" wire:model.defer="address" />
                    <x-input-error for="address" class="mt-2" />
                </div>

                <!-- Cidade -->
                <div class="col-span-1">
                    <x-label for="city" value="{{ __('Cidade') }}" />
                    <x-input id="city" type="text" class="mt-1 block w-full" wire:model.defer="city" />
                    <x-input-error for="city" class="mt-2" />
                </div>

                <!-- Estado -->
                <div class="col-span-1">
                    <x-label for="state" value="{{ __('Estado') }}" />
                    <x-input id="state" type="text" class="mt-1 block w-full mask-state" wire:model.defer="state" maxlength="2" />
                    <x-input-error for="state" class="mt-2" />
                </div>

                <!-- Telefone -->
                <div class="col-span-1">
                    <x-label for="phone" value="{{ __('Telefone') }}" />
                    <x-input id="phone" type="text" class="mt-1 block w-full mask-phone" wire:model.defer="phone" />
                    <x-input-error for="phone" class="mt-2" />
                </div>

                <!-- WhatsApp -->
                <div class="col-span-1">
                    <x-label for="whatsapp" value="{{ __('WhatsApp') }}" />
                    <x-input id="whatsapp" type="text" class="mt-1 block w-full mask-whatsapp" wire:model.defer="whatsapp" />
                    <x-input-error for="whatsapp" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="col-span-1">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="email" />
                    <x-input-error for="email" class="mt-2" />
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Cancelar') }}
                </a>
                <x-button>
                    {{ __('Salvar') }}
                </x-button>
            </div>
        </div>
    </form>

    <!-- Notifications -->
    <div id="notifications"></div>

    @if (session()->has('success'))
        <div class="fixed bottom-0 right-0 m-6">
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-0 right-0 m-6">
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyInputMasks();
            setupCepSearch();
            setupCnpjSearch();
            setupNotifications();
            
            // Para Livewire 3.x
            document.addEventListener('livewire:initialized', () => {
                // Configurar eventos do Livewire aqui
                setupLivewireEvents();
            });
            
            // Quando o Livewire atualiza o DOM
            document.addEventListener('livewire:update', () => {
                applyInputMasks();
                setupCepSearch();
                setupCnpjSearch();
            });
            
            function setupLivewireEvents() {
                // Registrar evento para notificações genéricas
                @this.on('notification', (event) => {
                    showNotification(event.message, event.type || 'info');
                });
                
                // Registrar evento para quando o CNPJ for encontrado
                @this.on('cnpj-found', (event) => {
                    if (event.message) {
                        showNotification(event.message, 'success');
                    }
                });
                
                // Registrar evento para quando o CNPJ não for encontrado mas deve permitir preenchimento manual
                @this.on('cnpj-not-found', (event) => {
                    console.log('CNPJ não encontrado nas APIs:', event.cnpj);
                    
                    // Remover indicadores visuais de carregamento
                    const loadingFields = ['company_name', 'trading_name', 'email', 'phone', 
                                        'address', 'city', 'state', 'zip_code', 'contact_person'];
                    
                    loadingFields.forEach(field => {
                        const element = document.getElementById(field);
                        if (element) {
                            element.value = '';
                            element.classList.remove('bg-gray-100');
                            element.removeAttribute('readonly');
                            element.placeholder = 'Preencha manualmente...';
                        }
                    });
                    
                    // Manter apenas o CNPJ formatado
                    const cnpjInput = document.getElementById('cnpj');
                    if (cnpjInput) {
                        cnpjInput.value = event.cnpj;
                        @this.set('cnpj', event.cnpj);
                    }
                    
                    // Colocar foco no campo de Razão Social para facilitar o preenchimento
                    setTimeout(() => {
                        const companyNameInput = document.getElementById('company_name');
                        if (companyNameInput) {
                            companyNameInput.focus();
                        }
                    }, 100);
                    
                    showNotification(event.message, 'warning');
                    
                    // Mostrar uma dica adicional após 1 segundo
                    setTimeout(() => {
                        showNotification('Você pode continuar o cadastro preenchendo os campos manualmente.', 'info');
                    }, 1000);
                });
                
                // Registrar evento para atualização de campos específicos
                @this.on('update-field', (data) => {
                    console.log('Atualizando campo específico:', data.field, data.value);
                    
                    const input = document.getElementById(data.field);
                    if (input && data.value) {
                        // Salvar a instância da máscara atual (se existir)
                        let maskInstance = null;
                        if (input.maskInstance) {
                            maskInstance = input.maskInstance;
                            maskInstance.destroy();
                        }
                        
                        // Atualizar o valor
                        input.value = data.value;
                        @this.set(data.field, data.value);
                        
                        // Se havia máscara, recriar
                        if (maskInstance) {
                            setTimeout(() => {
                                applyMaskToElement(input);
                            }, 50);
                        }
                        
                        showNotification(`Campo ${data.field} atualizado com sucesso!`, 'success');
                    }
                });
                
                // Registrar evento para atualização de campos vindos do backend
                @this.on('input', (data) => {
                    console.log('Dados recebidos do backend:', data);
                    
                    // Remover todas as máscaras antes de atualizar os campos
                    destroyMasks();
                    
                    // Verificar especificamente o nome fantasia
                    if (data.trading_name) {
                        console.log('Nome fantasia recebido:', data.trading_name);
                    }
                    
                    // Atualizar os campos do formulário
                    Object.keys(data).forEach(key => {
                        const input = document.getElementById(key);
                        if (input) {
                            // Garantir que valores vazios não sejam usados
                            if (data[key] !== null && data[key] !== undefined && data[key] !== '') {
                                input.value = data[key];
                                // Atualizar o modelo Livewire
                                @this.set(key, data[key]);
                                
                                // Para o nome fantasia, verificar se não está vazio ou é igual à razão social
                                if (key === 'trading_name') {
                                    console.log('Atualizando nome fantasia para:', data[key]);
                                }
                            }
                        }
                    });
                    
                    // Recriar as máscaras após atualizar os valores
                    setTimeout(() => {
                        applyInputMasks();
                    }, 100);
                    
                    showNotification('Campos preenchidos com sucesso!', 'success');
                });
                
                // Registrar evento para erros na busca de CNPJ
                @this.on('cnpj-error', (event) => {
                    showNotification(event.message, 'error');
                    clearLoadingState();
                });
                
                // Registrar eventos de sucesso e erro no fornecedor
                @this.on('supplier-saved', (event) => {
                    showNotification(event.message, 'success');
                });
                
                @this.on('supplier-error', (event) => {
                    showNotification(event.message, 'error');
                });
            }
            
            function setupNotifications() {
                // Criar container para notificações se não existir
                if (!document.getElementById('notifications')) {
                    const notificationContainer = document.createElement('div');
                    notificationContainer.id = 'notifications';
                    notificationContainer.className = 'fixed bottom-0 right-0 m-6 z-50';
                    document.body.appendChild(notificationContainer);
                }
            }
            
            function showNotification(message, type = 'info') {
                const container = document.getElementById('notifications');
                
                const notification = document.createElement('div');
                
                // Definir as classes com base no tipo
                let bgColor = 'bg-blue-500';
                if (type === 'success') bgColor = 'bg-green-500';
                if (type === 'error') bgColor = 'bg-red-500';
                if (type === 'warning') bgColor = 'bg-yellow-500';
                
                notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg mb-3 opacity-100 transition-opacity duration-500`;
                notification.textContent = message;
                
                container.appendChild(notification);
                
                // Remover depois de 6 segundos (aumentado de 5 para 6 segundos)
                setTimeout(() => {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 6000); // Tempo aumentado para 6 segundos
            }
            
            function applyInputMasks() {
                // CNPJ Mask
                document.querySelectorAll('.mask-cnpj').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                    }
                    element.maskInstance = IMask(element, {
                        mask: '00.000.000/0000-00'
                    });
                });
                
                // CEP Mask
                document.querySelectorAll('.mask-cep').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                    }
                    element.maskInstance = IMask(element, {
                        mask: '00000-000',
                        prepare: (value) => {
                            // Remove todos os caracteres não numéricos e aplica a máscara
                            return value.replace(/[^\d]/g, '');
                        }
                    });
                    
                    // Força a atualização da máscara com o valor atual
                    if (element.value) {
                        element.maskInstance.value = element.value;
                        element.maskInstance.updateValue();
                    }
                });
                
                // Phone Mask
                document.querySelectorAll('.mask-phone').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                    }
                    element.maskInstance = IMask(element, {
                        mask: [
                            { mask: '(00) 0000-0000' },
                            { mask: '(00) 00000-0000' }
                        ],
                        // Função para limpar o valor antes de aplicar a máscara
                        prepare: (value) => {
                            return value.replace(/[^\d]/g, '');
                        },
                        // Função para detectar a máscara automaticamente
                        dispatch: function (appended, dynamicMasked) {
                            const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                            
                            return dynamicMasked.compiledMasks.find(function (m) {
                                const re = m.mask === '(00) 0000-0000' ? /^\d{10}$/ : /^\d{11}$/;
                                return number.match(re);
                            }) || this.compiledMasks[0];
                        }
                    });
                    
                    // Força a atualização da máscara com o valor atual
                    if (element.value) {
                        element.maskInstance.value = element.value;
                        element.maskInstance.updateValue();
                    }
                });
                
                // WhatsApp Mask
                document.querySelectorAll('.mask-whatsapp').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                    }
                    element.maskInstance = IMask(element, {
                        mask: '(00) 00000-0000',
                        prepare: (value) => {
                            return value.replace(/[^\d]/g, '');
                        }
                    });
                    
                    // Força a atualização da máscara com o valor atual
                    if (element.value) {
                        element.maskInstance.value = element.value;
                        element.maskInstance.updateValue();
                    }
                });
                
                // Estado Mask
                document.querySelectorAll('.mask-state').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                    }
                    element.maskInstance = IMask(element, {
                        mask: /^[A-Z]{0,2}$/,
                        prepare: (str) => str.toUpperCase()
                    });
                    
                    // Força a atualização da máscara com o valor atual
                    if (element.value) {
                        element.maskInstance.value = element.value;
                        element.maskInstance.updateValue();
                    }
                });
            }
            
            function destroyMasks() {
                // Destroy all mask instances
                document.querySelectorAll('.mask-cnpj, .mask-cep, .mask-phone, .mask-whatsapp, .mask-state').forEach(element => {
                    if (element.maskInstance) {
                        element.maskInstance.destroy();
                        element.maskInstance = null;
                    }
                });
            }
            
            function setupCepSearch() {
                const cepInput = document.getElementById('zip_code');
                const searchButton = document.getElementById('searchCEP');
                
                // Função para buscar CEP no botão
                if (searchButton) {
                    searchButton.addEventListener('click', function() {
                        searchCEP();
                    });
                }
                
                // Função para buscar CEP ao sair do campo
                if (cepInput) {
                    cepInput.addEventListener('blur', function() {
                        searchCEP();
                    });
                }
                
                function searchCEP() {
                    const cep = cepInput.value.replace(/\D/g, '');
                    
                    if (cep.length !== 8) {
                        return;
                    }
                    
                    // Mostrar loading
                    document.getElementById('address').value = 'Carregando...';
                    document.getElementById('city').value = 'Carregando...';
                    document.getElementById('state').value = '';
                    
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.erro) {
                                showNotification('CEP não encontrado.', 'error');
                                document.getElementById('address').value = '';
                                document.getElementById('city').value = '';
                                document.getElementById('state').value = '';
                                return;
                            }
                            
                            // Atualizar os campos com os dados do CEP
                            const address = `${data.logradouro}, ${data.bairro}`;
                            
                            // Formatar o CEP no formato XXXXX-XXX
                            const formattedCep = cep.substring(0, 5) + '-' + cep.substring(5, 8);
                            
                            // Atualizar os campos do Livewire
                            @this.set('address', address);
                            @this.set('city', data.localidade);
                            @this.set('state', data.uf);
                            @this.set('zip_code', formattedCep);
                            
                            // Atualizar os campos visíveis para o usuário
                            document.getElementById('address').value = address;
                            document.getElementById('city').value = data.localidade;
                            document.getElementById('state').value = data.uf;
                            document.getElementById('zip_code').value = formattedCep;
                            
                            // Mostrar notificação de sucesso
                            showNotification('CEP encontrado com sucesso!', 'success');
                        })
                        .catch(error => {
                            console.error('Erro ao buscar CEP:', error);
                            showNotification('Erro ao buscar CEP. Tente novamente mais tarde.', 'error');
                            document.getElementById('address').value = '';
                            document.getElementById('city').value = '';
                            document.getElementById('state').value = '';
                        });
                }
            }
            
            function setupCnpjSearch() {
                const cnpjInput = document.getElementById('cnpj');
                const searchButton = document.getElementById('searchCNPJ');
                
                // Função para buscar CNPJ no botão
                if (searchButton) {
                    searchButton.addEventListener('click', function() {
                        searchCNPJ();
                    });
                }
                
                // Função para buscar CNPJ ao sair do campo
                if (cnpjInput) {
                    cnpjInput.addEventListener('blur', function() {
                        if (cnpjInput.value.replace(/\D/g, '').length === 14) {
                            searchCNPJ();
                        }
                    });
                }
                
                function searchCNPJ() {
                    const cnpj = cnpjInput.value.replace(/\D/g, '');
                    
                    if (cnpj.length !== 14) {
                        showNotification('CNPJ deve ter 14 dígitos', 'error');
                        return;
                    }
                    
                    // Indicadores visuais de carregamento
                    const loadingFields = ['company_name', 'trading_name', 'email', 'phone', 
                                         'address', 'city', 'state', 'zip_code'];
                    
                    loadingFields.forEach(field => {
                        const element = document.getElementById(field);
                        if (element) {
                            element.classList.add('bg-gray-100');
                            element.value = 'Carregando...';
                            // Desativar campo durante o carregamento
                            element.setAttribute('readonly', 'readonly');
                        }
                    });
                    
                    // Desabilitar o botão de busca durante a pesquisa
                    searchButton.disabled = true;
                    searchButton.classList.add('opacity-50', 'cursor-not-allowed');
                    
                    // Limpar campo de WhatsApp explicitamente (não será preenchido automaticamente)
                    document.getElementById('whatsapp').value = '';
                    @this.set('whatsapp', '');
                    
                    console.log('Consultando CNPJ:', cnpj);
                    showNotification('Consultando CNPJ...', 'info');
                    
                    // Definir um timeout de 10 segundos para a consulta
                    const timeoutId = setTimeout(() => {
                        // Se o timeout for atingido, limpar os campos e notificar o usuário
                        console.log('Timeout de 10 segundos atingido na consulta do CNPJ:', cnpj);
                        
                        // Restaurar o botão de busca
                        searchButton.disabled = false;
                        searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        
                        // Limpar os campos que estavam carregando
                        clearLoadingState();
                        
                        // Manter o CNPJ formatado
                        if (cnpjInput) {
                            const formattedCnpj = formatCNPJ(cnpj);
                            cnpjInput.value = formattedCnpj;
                            @this.set('cnpj', formattedCnpj);
                        }
                        
                        // Notificar o usuário
                        showNotification('Tempo limite excedido. Preencha os campos manualmente.', 'warning');
                        
                        // Colocar foco no campo de Razão Social para facilitar o preenchimento
                        setTimeout(() => {
                            const companyNameInput = document.getElementById('company_name');
                            if (companyNameInput) {
                                companyNameInput.focus();
                            }
                        }, 100);
                    }, 10000); // 10 segundos
                    
                    // Chamar o método do Livewire
                    @this.fetchCNPJ(cnpj)
                        .finally(() => {
                            // Independente do resultado, restaura o botão
                            searchButton.disabled = false;
                            searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
                            
                            // Limpar o timeout se a resposta chegou antes do limite
                            clearTimeout(timeoutId);
                        });
                }
                
                // Função para formatar CNPJ no frontend
                function formatCNPJ(cnpj) {
                    if (cnpj.length !== 14) {
                        return cnpj;
                    }
                    
                    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
                }
                
                function clearLoadingState() {
                    // Restaurar os campos para que possam ser editados
                    const loadingFields = ['company_name', 'trading_name', 'email', 'phone', 
                                         'address', 'city', 'state', 'zip_code'];
                                          
                    loadingFields.forEach(field => {
                        const element = document.getElementById(field);
                        if (element && element.value === 'Carregando...') {
                            element.value = '';
                            element.classList.remove('bg-gray-100');
                            element.removeAttribute('readonly');
                        }
                    });
                }
                
                function clearFields() {
                    // Primeiro destrua as máscaras
                    destroyMasks();
                    
                    // Limpar campos
                    const fields = ['company_name', 'trading_name', 'email', 'phone', 
                                  'address', 'city', 'state', 'zip_code', 'whatsapp', 'contact_person'];
                                  
                    fields.forEach(field => {
                        const element = document.getElementById(field);
                        if (element) {
                            element.value = '';
                            element.classList.remove('bg-gray-100');
                            element.removeAttribute('readonly');
                            @this.set(field, '');
                        }
                    });
                    
                    // Reaplique as máscaras
                    applyInputMasks();
                }
            }

            function applyMaskToElement(element) {
                if (element.classList.contains('mask-cnpj')) {
                    element.maskInstance = IMask(element, {
                        mask: '00.000.000/0000-00'
                    });
                } else if (element.classList.contains('mask-cep')) {
                    element.maskInstance = IMask(element, {
                        mask: '00000-000'
                    });
                } else if (element.classList.contains('mask-phone')) {
                    element.maskInstance = IMask(element, {
                        mask: [
                            { mask: '(00) 0000-0000' },
                            { mask: '(00) 00000-0000' }
                        ],
                        dispatch: function (appended, dynamicMasked) {
                            const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                            return dynamicMasked.compiledMasks.find(function (m) {
                                const re = m.mask === '(00) 0000-0000' ? /^\d{10}$/ : /^\d{11}$/;
                                return number.match(re);
                            }) || this.compiledMasks[0];
                        }
                    });
                } else if (element.classList.contains('mask-whatsapp')) {
                    element.maskInstance = IMask(element, {
                        mask: '(00) 00000-0000'
                    });
                } else if (element.classList.contains('mask-state')) {
                    element.maskInstance = IMask(element, {
                        mask: /^[A-Z]{0,2}$/,
                        prepare: (str) => str.toUpperCase()
                    });
                }
                
                if (element.maskInstance && element.value) {
                    element.maskInstance.value = element.value;
                    element.maskInstance.updateValue();
                }
            }
        });
    </script>
    @endpush
</div>
