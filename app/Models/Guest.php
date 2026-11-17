<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'phone',
        'email',
        'notes',
        'confirmed_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function companions(): HasMany
    {
        return $this->hasMany(Companion::class);
    }

    public function totalGroupCount(): int
    {
        return 1 + $this->companions()->count();
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->confirmed_at ? $this->confirmed_at->locale('pt_BR')->diffForHumans() : '';
    }

    public static function normalizeName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $clean = preg_replace('/\s+/', ' ', trim($name));
        return mb_strtolower($clean, 'UTF-8');
    }

    public static function normalizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '55') && strlen($digits) >= 12) {
            $digits = substr($digits, 2);
        }

        return $digits ?: null;
    }
}
