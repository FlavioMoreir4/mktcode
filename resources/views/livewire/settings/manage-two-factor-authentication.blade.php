<div class="space-y-6">
    @php
        $user = auth()->user();
        $isTwoFactorEnabled = $user->two_factor_secret && $user->two_factor_confirmed_at;
        $isTwoFactorPending = $user->two_factor_secret && !$user->two_factor_confirmed_at;
    @endphp

    <div class="flex items-center justify-between p-4 border rounded-lg bg-gray-50/50">
        <div class="space-y-1">
            <h3 class="text-sm font-medium">Autenticação de Dois Fatores (2FA)</h3>
            <p class="text-xs text-gray-500">Adicione uma camada extra de segurança à sua conta.</p>
        </div>
        
        @if ($isTwoFactorEnabled)
            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                Ativado
            </span>
        @else
            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                Desativado
            </span>
        @endif
    </div>

    @if (!$isTwoFactorEnabled && !$isTwoFactorPending)
        <div class="p-4 border border-dashed rounded-lg">
            <div class="text-sm text-gray-600 mb-4">
                Ao ativar a autenticação de dois fatores, você será solicitado a fornecer um código PIN seguro durante o login. Você pode obter esse código no aplicativo Google Authenticator ou similar no seu telefone.
            </div>
            
            <button
                type="button"
                wire:click="enableTwoFactorAuthentication"
                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-black rounded-md shadow-sm hover:bg-gray-800"
            >
                Ativar 2FA
            </button>
        </div>
    @endif

    @if ($isTwoFactorPending)
        <div class="p-4 border border-yellow-200 bg-yellow-50 rounded-lg space-y-4">
            <h4 class="text-sm font-semibold text-yellow-800">Finalize a configuração do 2FA</h4>
            
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0 p-2 bg-white rounded-lg border border-yellow-200">
                    {!! $user->twoFactorQrCodeSvg() !!}
                </div>
                
                <div class="space-y-4 flex-1">
                    <p class="text-sm text-yellow-700">
                        Escaneie o código QR acima com o seu aplicativo de autenticação. Em seguida, insira o código de 6 dígitos gerado para confirmar.
                    </p>

                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="code"
                            placeholder="000000"
                            class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm"
                        >
                        <button
                            type="button"
                            wire:click="confirmTwoFactorAuthentication"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-black rounded-md shadow-sm hover:bg-gray-800"
                        >
                            Confirmar Código
                        </button>
                        <button
                            type="button"
                            wire:click="disableTwoFactorAuthentication"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isTwoFactorEnabled)
        <div class="space-y-4">
            <div class="flex flex-wrap gap-2">
                <button
                    type="button"
                    wire:click="disableTwoFactorAuthentication"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-md shadow-sm hover:bg-red-500"
                >
                    Desativar 2FA
                </button>

                @if (!$showingRecoveryCodes)
                    <button
                        type="button"
                        wire:click="$set('showingRecoveryCodes', true)"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Mostrar Códigos de Recuperação
                    </button>
                @endif
            </div>

            @if ($showingRecoveryCodes)
                <div class="p-4 bg-gray-900 text-white rounded-lg space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold">Códigos de Recuperação</h4>
                        <button
                            type="button"
                            wire:click="regenerateRecoveryCodes"
                            class="text-xs text-gray-400 hover:text-white"
                        >
                            Gerar novos códigos
                        </button>
                    </div>
                    
                    <p class="text-xs text-gray-400">
                        Guarde estes códigos em um lugar seguro. Eles podem ser usados para acessar sua conta se você perder o acesso ao seu dispositivo de autenticação.
                    </p>

                    <div class="grid grid-cols-2 gap-2 font-mono text-sm tracking-widest text-center">
                        @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $recoveryCode)
                            <div class="p-1 bg-gray-800 rounded">{{ $recoveryCode }}</div>
                        @endforeach
                    </div>

                    <button
                        type="button"
                        wire:click="$set('showingRecoveryCodes', false)"
                        class="w-full py-2 text-xs font-semibold text-gray-400 border border-gray-700 rounded hover:bg-gray-800 hover:text-white"
                    >
                        Ocultar Códigos
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
