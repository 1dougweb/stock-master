<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div style="width: 90px; height: 90px;">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                    viewBox="0 0 485 485" xml:space="preserve" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g id="XMLID_31_">
                                <g>
                                    <rect x="62.5" y="147.5" style="fill:#3730a3;" width="150" height="35"></rect>
                                    <rect x="312.5" y="142.5" style="fill:#E7ECED;" width="50" height="90"></rect>
                                    <rect x="97.5" y="252.5" style="fill:#E7ECED;" width="60" height="85"></rect>
                                    <polygon style="fill:#3730a3;" points="477.5,322.5 477.5,432.5 327.5,432.5 327.5,322.5 427.5,322.5"></polygon>
                                    <polygon style="fill:#818cf8;" points="212.5,182.5 212.5,252.5 157.5,252.5 97.5,252.5 62.5,252.5 62.5,182.5"></polygon>
                                    <polygon style="fill:#818cf8;" points="427.5,142.5 427.5,322.5 327.5,322.5 327.5,432.5 247.5,432.5 247.5,252.5 247.5,142.5 297.5,142.5 312.5,142.5 312.5,232.5 362.5,232.5 362.5,142.5"></polygon>
                                    <polygon style="fill:#3730a3;" points="427.5,52.5 427.5,142.5 362.5,142.5 312.5,142.5 297.5,142.5 297.5,52.5"></polygon>
                                    <polygon style="fill:#3730a3;" points="247.5,252.5 247.5,432.5 7.5,432.5 7.5,252.5 62.5,252.5 97.5,252.5 97.5,337.5 157.5,337.5 157.5,252.5 212.5,252.5"></polygon>
                                </g>
                                <g>
                                    <path d="M435,315V150v-7.5V45H290v90h-50v110h-20V140H55v105H0v195h485V315H435z M305,60h115v75H305V60z M355,150v75h-35v-75H355z M70,245v-55h135v55H70z M150,260v70h-45v-70H150z M205,155v20H70v-20H205z M15,425V260h75v85h75v-85h75v165H15z M255,425V150h50v90h65v-90h50v165H320v110H255z M470,425H335v-95h135V425z"></path>
                                    <rect x="167.5" y="210" width="20" height="15"></rect>
                                    <rect x="137.5" y="210" width="20" height="15"></rect>
                                    <rect x="407.5" y="355" width="40" height="15"></rect>
                                    <rect x="377.5" y="385" width="70" height="15"></rect>
                                    <rect x="377.5" y="100" width="20" height="15"></rect>
                                    <rect x="347.5" y="100" width="20" height="15"></rect>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Senha') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Lembrar-me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Esqueceu sua senha?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Entrar') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
