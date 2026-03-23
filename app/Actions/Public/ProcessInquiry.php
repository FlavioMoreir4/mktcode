<?php

declare(strict_types=1);

namespace App\Actions\Public;

use App\Application\Inquiries\Actions\SubmitInquiry;

/**
 * Backwards-compatible bridge while public controllers move to the application use case namespace.
 */
class ProcessInquiry
{
    public function __construct(private readonly SubmitInquiry $submitInquiry) {}

    /**
     * @param  array{name: string, email: string, message: string, whatsapp?: string|null}  $data
     */
    public function execute(array $data): void
    {
        $this->submitInquiry->handle($data);
    }
}
