<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                <!-- CNPJ -->
                <div class="col-span-1">
                    <x-label for="cnpj" value="{{ __('CNPJ') }}" />
                    <x-input id="cnpj" type="text" class="mt-1 block w-full" wire:model.defer="cnpj" />
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

                <!-- Endereço -->
                <div class="col-span-2">
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
                    <x-input id="state" type="text" class="mt-1 block w-full" wire:model.defer="state" maxlength="2" />
                    <x-input-error for="state" class="mt-2" />
                </div>

                <!-- CEP -->
                <div class="col-span-1">
                    <x-label for="zip_code" value="{{ __('CEP') }}" />
                    <x-input id="zip_code" type="text" class="mt-1 block w-full" wire:model.defer="zip_code" />
                    <x-input-error for="zip_code" class="mt-2" />
                </div>

                <!-- Telefone -->
                <div class="col-span-1">
                    <x-label for="phone" value="{{ __('Telefone') }}" />
                    <x-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="phone" />
                    <x-input-error for="phone" class="mt-2" />
                </div>

                <!-- WhatsApp -->
                <div class="col-span-1">
                    <x-label for="whatsapp" value="{{ __('WhatsApp') }}" />
                    <x-input id="whatsapp" type="text" class="mt-1 block w-full" wire:model.defer="whatsapp" />
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
</div>
