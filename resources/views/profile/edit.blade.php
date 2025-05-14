<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Card Único -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-4 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Coluna Esquerda - Informações do Perfil -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Informações de Perfil') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Atualize as informações do seu perfil e endereço de e-mail.') }}
                            </p>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                <div>
                                    <x-label for="name" value="{{ __('Nome') }}" />
                                    <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                                    <x-input-error for="name" class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-label for="email" value="{{ __('E-mail') }}" />
                                    <x-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                                    <x-input-error for="email" class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-label for="phone" value="{{ __('Telefone') }}" />
                                    <x-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                    <x-input-error for="phone" class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <x-label for="position" value="{{ __('Cargo') }}" />
                                    <x-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position', $user->position)" />
                                    <x-input-error for="position" class="mt-2" :messages="$errors->get('position')" />
                                </div>

                                <div>
                                    <x-label for="department" value="{{ __('Departamento') }}" />
                                    <x-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $user->department)" />
                                    <x-input-error for="department" class="mt-2" :messages="$errors->get('department')" />
                                </div>

                                <div>
                                    <x-label for="bio" value="{{ __('Biografia') }}" />
                                    <textarea id="bio" name="bio" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" rows="4">{{ old('bio', $user->bio) }}</textarea>
                                    <x-input-error for="bio" class="mt-2" :messages="$errors->get('bio')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-button>
                                        {{ __('Salvar') }}
                                    </x-button>
                                </div>
                            </form>
                        </div>

                        <!-- Coluna Direita - Foto e Senha -->
                        <div class="space-y-6">
                            <!-- Foto de Perfil -->
                            <div class="mb-10">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Foto de Perfil') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Atualize sua foto de perfil.') }}
                                </p>

                                <div class="mt-6 flex items-center">
                                    <div class="mr-4">
                                        @if ($user->profile_photo)
                                            <img src="{{ Storage::disk('public')->url($user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-full h-20 w-20 object-cover">
                                        @else
                                            <div class="rounded-full h-20 w-20 bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-xl">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <form method="post" action="{{ route('profile.photo') }}" class="ml-4" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div>
                                            <x-label for="profile_photo" value="{{ __('Nova Foto') }}" />
                                            <x-input id="profile_photo" name="profile_photo" type="file" class="mt-1 block w-full" required />
                                            <x-input-error for="profile_photo" class="mt-2" :messages="$errors->get('profile_photo')" />
                                        </div>

                                        <div class="flex items-center gap-4 mt-4">
                                            <x-button>
                                                {{ __('Atualizar Foto') }}
                                            </x-button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Alterar Senha -->
                            <div class="pt-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Alterar Senha') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.') }}
                                </p>

                                <form method="post" action="{{ route('profile.password') }}" class="mt-6 space-y-6">
                                    @csrf
                                    @method('put')

                                    <div>
                                        <x-label for="current_password" value="{{ __('Senha Atual') }}" />
                                        <x-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" />
                                        <x-input-error for="current_password" :messages="$errors->get('current_password')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-label for="password" value="{{ __('Nova Senha') }}" />
                                        <x-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                        <x-input-error for="password" :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-label for="password_confirmation" value="{{ __('Confirmar Senha') }}" />
                                        <x-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                                        <x-input-error for="password_confirmation" :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-button>
                                            {{ __('Salvar') }}
                                        </x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 