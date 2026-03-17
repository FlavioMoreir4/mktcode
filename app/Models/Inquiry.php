<?php

namespace App\Models;

use Database\Factories\InquiryFactory;
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

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'new' => 'Novo',
            'in_progress' => 'Em atendimento',
            'resolved' => 'Resolvido',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'new' => 'info',
            'in_progress' => 'warning',
            'resolved' => 'success',
            default => 'gray',
        };
    }
}
