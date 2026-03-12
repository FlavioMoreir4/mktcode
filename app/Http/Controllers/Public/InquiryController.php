declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Actions\Public\ProcessInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\InquiryRequest;
use Illuminate\Http\RedirectResponse;

class InquiryController extends Controller
{
    public function __invoke(InquiryRequest $request, ProcessInquiry $processInquiry): RedirectResponse
    {
        $processInquiry->execute($request->validated());

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}
