<?php

namespace App\Livewire\Settings;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

class ManageTwoFactorAuthentication extends Component
{
    public bool $showingQrCode = false;
    public bool $showingRecoveryCodes = false;
    public bool $showingConfirmation = false;
    public string $code = '';

    public function mount(): void
    {
        // Se já tiver o segredo mas não confirmado, mostrar confirmação
        if (Auth::user()->two_factor_secret && ! Auth::user()->two_factor_confirmed_at) {
            $this->showingConfirmation = true;
        }
    }

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        $enable(Auth::user());

        $this->showingQrCode = true;
        $this->showingConfirmation = true;

        Notification::make()
            ->title('2FA Iniciado')
            ->body('Por favor, escaneie o código QR e confirme o código gerado.')
            ->success()
            ->send();
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm): void
    {
        $confirm(Auth::user(), $this->code);

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = true;

        Notification::make()
            ->title('2FA Confirmado')
            ->body('A autenticação de dois fatores foi ativada com sucesso.')
            ->success()
            ->send();
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;

        Notification::make()
            ->title('2FA Desativado')
            ->body('A autenticação de dois fatores foi desativada.')
            ->success()
            ->send();
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $generate(Auth::user());

        $this->showingRecoveryCodes = true;

        Notification::make()
            ->title('Novos Códigos de Recuperação')
            ->body('Novos códigos de recuperação foram gerados.')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.settings.manage-two-factor-authentication');
    }
}
