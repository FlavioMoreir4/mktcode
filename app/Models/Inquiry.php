<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Inquiry\Enums\InquiryStatus;
use Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** @use HasFactory<InquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'message',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
        ];
    }

    public function scopeStatus(Builder $query, InquiryStatus|string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', InquiryStatus::New)
            ->where('created_at', '<', now()->subHours(24));
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at');
    }
}
