<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Funcionário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome -->
                            <div>
                                <x-label for="name" value="{{ __('Nome') }}" />
                                <x-input id="name" type="text" class="mt-1 block w-full" name="name" :value="old('name', $employee->user->name)" required autofocus />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" class="mt-1 block w-full" name="email" :value="old('email', $employee->user->email)" required />
                                <x-input-error for="email" class="mt-2" />
                            </div>

                            <!-- Cargo -->
                            <div>
                                <x-label for="position" value="{{ __('Cargo') }}" />
                                <x-input id="position" type="text" class="mt-1 block w-full" name="position" :value="old('position', $employee->position)" required />
                                <x-input-error for="position" class="mt-2" />
                            </div>

                            <!-- Departamento -->
                            <div>
                                <x-label for="department" value="{{ __('Departamento') }}" />
                                <x-input id="department" type="text" class="mt-1 block w-full" name="department" :value="old('department', $employee->department)" required />
                                <x-input-error for="department" class="mt-2" />
                            </div>

                            <!-- Telefone -->
                            <div>
                                <x-label for="phone" value="{{ __('Telefone') }}" />
                                <x-input id="phone" type="text" class="mt-1 block w-full" name="phone" :value="old('phone', $employee->phone)" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Endereço -->
                        <div>
                            <x-label for="address" value="{{ __('Endereço') }}" />
                            <x-input id="address" name="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('address', $employee->address) }}</x-input>
                            <x-input-error for="address" class="mt-2" />
                        </div>
                        </div>



                        <!-- Observações -->
                        <div class="mt-6">
                            <x-label for="notes" value="{{ __('Observações') }}" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes', $employee->notes) }}</textarea>
                            <x-input-error for="notes" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancelar') }}
                            </a>

                            <x-button>
                                {{ __('Salvar') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
