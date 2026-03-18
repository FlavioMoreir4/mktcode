<?php

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
        return $query->orderByDesc(fn ($q) => $q->selectRaw("status = 'new'"));
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->whereNotNull('updated_at')
            ->whereColumn('updated_at', '>', 'created_at');
    }

    public function scopeMetSla(Builder $query): Builder
    {
        $driver = config('database.default');

        return $query->where(function ($q) use ($driver) {
            if ($driver === 'sqlite') {
                $q->whereRaw("(strftime('%s', updated_at) - strftime('%s', created_at)) <= 86400");
            } else {
                $q->whereRaw('TIMESTAMPDIFF(HOUR, created_at, updated_at) <= 24');
            }
        });
    }

    public static function getDashboardCounts(): ?object
    {
        return static::selectRaw("
            SUM(status = 'new') as pending,
            SUM(status = 'in_progress') as in_progress,
            SUM(status = 'resolved') as resolved,
            SUM(DATE(created_at) = DATE('now')) as today,
            SUM(status = 'new' AND created_at < ?) as late
        ", [now()->subHours(24)])->first();
    }

    public static function getAvgResponseTime(): float
    {
        $driver = config('database.default');

        $avg = static::resolved()
            ->when($driver === 'sqlite', fn ($q) => $q->selectRaw(
                "AVG((strftime('%s', updated_at) - strftime('%s', created_at)) / 3600.0) as avg_time"
            ))
            ->when($driver !== 'sqlite', fn ($q) => $q->selectRaw('
                AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time
            '))
            ->value('avg_time');

        return round((float) $avg, 1);
    }

    public static function getActivityChartData(int $days = 7): array
    {
        $data = static::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total
            ')
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = collect(range($days - 1, 0))->map(function ($i) {
            return now()->subDays($i)->format('d/m');
        });

        $values = collect(range($days - 1, 0))->map(function ($i) use ($data) {
            $date = now()->subDays($i)->toDateString();

            return $data[$date] ?? 0;
        });

        return [
            'labels' => $labels->toArray(),
            'values' => $values->toArray(),
        ];
    }
}
