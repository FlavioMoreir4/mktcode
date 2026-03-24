<?php

declare(strict_types=1);

namespace App\Application\Inquiry\DTOs;

final readonly class SubmitInquiryData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $message,
        public ?string $whatsapp = null,
    ) {}

    /**
     * @param  array{name: string, email: string, message: string, whatsapp?: string|null}  $attributes
     */
    public static function fromArray(array $attributes): self
    {
        return new self(
            name: $attributes['name'],
            email: $attributes['email'],
            message: $attributes['message'],
            whatsapp: $attributes['whatsapp'] ?? null,
        );
    }

    /**
     * @return array{name: string, email: string, message: string, whatsapp?: string|null}
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'whatsapp' => $this->whatsapp,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
