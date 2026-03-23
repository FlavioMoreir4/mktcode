<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InquiryStatus;
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

    public function getStatusLabel(): string
    {
        return $this->status instanceof InquiryStatus
            ? $this->status->getLabel()
            : (string) $this->status;
    }

    public function getStatusColor(): string
    {
        return $this->status instanceof InquiryStatus
            ? (string) $this->status->getColor()
            : 'gray';
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

    public function scopePrioritizeNew(Builder $query): Builder
    {
        return $query->orderByRaw(
            'CASE WHEN status = ? THEN 0 ELSE 1 END',
            [InquiryStatus::New->value],
        );
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at');
    }
}
