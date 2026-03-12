declare(strict_types=1);

namespace App\Actions\Public;

class ProcessInquiry
{
    public function execute(array $data): void
    {
        // TODO: Enviar notificação por e-mail ou Telegram
        // Por enquanto, apenas log para demonstração ou salvar no banco se houvesse model
        \Log::info('New Inquiry Received', $data);
    }
}
