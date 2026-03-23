<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Application\Inquiries\Actions\SubmitInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\InquiryRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Public contact form adapter. Validation stays in the request, orchestration in the application layer.
 */
class InquiryController extends Controller
{
    public function store(InquiryRequest $request, SubmitInquiry $submitInquiry): RedirectResponse
    {
        $submitInquiry->handle($request->validated());

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}
