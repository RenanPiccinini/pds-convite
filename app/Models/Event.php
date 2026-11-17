<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'event_date',
        'rsvp_deadline',
        'location_name',
        'location_address',
        'location_map_url',
        'cover_image',
        'theme',
        'allow_companions',
        'max_companions',
        'whatsapp_message',
        'status',
        'is_unlimited',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'rsvp_deadline' => 'datetime',
            'allow_companions' => 'boolean',
            'is_unlimited' => 'boolean',
            'max_companions' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class)->orderBy('created_at', 'desc');
    }

    public function companions(): HasManyThrough
    {
        return $this->hasManyThrough(Companion::class, Guest::class);
    }

    public function isRsvpOpen(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return now()->lessThanOrEqualTo($this->rsvp_deadline);
    }

    public function isPast(): bool
    {
        return $this->event_date->isPast();
    }

    public function totalConfirmedGuests(): int
    {
        return $this->guests()->count();
    }

    public function totalConfirmedCompanions(): int
    {
        return $this->companions()->count();
    }

    public function totalAttendeesCount(): int
    {
        return $this->totalConfirmedGuests() + $this->totalConfirmedCompanions();
    }

    public function getMapUrlAttribute(): string
    {
        if (! empty($this->location_map_url)) {
            return $this->location_map_url;
        }

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($this->location_address);
    }

    public function getFormattedEventDateAttribute(): string
    {
        return $this->event_date->locale('pt_BR')->translatedFormat('d \d\e M. \d\e Y \à\s H:i');
    }

    public function getFormattedRsvpDeadlineAttribute(): string
    {
        return $this->rsvp_deadline->locale('pt_BR')->translatedFormat('d \d\e M. \d\e Y \à\s H:i');
    }

    public function getDaysUntilEventAttribute(): string
    {
        $now = now()->startOfDay();
        $target = $this->event_date->copy()->startOfDay();

        if ($target->isPast() && ! $target->isToday()) {
            return 'Encerrado';
        }

        $diff = $now->diffInDays($target, false);

        if ($diff == 0) {
            return 'Hoje';
        }

        if ($diff == 1) {
            return 'Amanhã';
        }

        return "em {$diff} dias";
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/convite/'.$this->slug);
    }

    public function getWhatsAppShareTextAttribute(): string
    {
        $url = $this->public_url;
        $title = $this->title;
        $date = $this->formatted_event_date;
        $loc = $this->location_address;

        return "🎉 *Você foi convidado(a) para:* {$title}\n\n"
            ."📅 *Quando:* {$date}\n"
            ."📍 *Onde:* {$loc}\n\n"
            ."👉 Por favor, confirme sua presença clicando no link abaixo:\n"
            ."{$url}\n\n"
            .'_PDS Convite - Piccinini Digital Solutions_';
    }

    public function getWhatsAppShareUrlAttribute(): string
    {
        return 'https://api.whatsapp.com/send?text='.urlencode($this->whats_app_share_text);
    }

    public function getGoogleCalendarUrlAttribute(): string
    {
        $start = $this->event_date->format('Ymd\THis');
        $end = $this->event_date->copy()->addHours(3)->format('Ymd\THis');

        return 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.urlencode($this->title)
            .'&dates='.$start.'/'.$end
            .'&details='.urlencode("Confirmação de presença via PDS Convite: {$this->public_url}")
            .'&location='.urlencode($this->location_address);
    }

    public function findDuplicateGuest(string $name, ?string $phone = null, ?int $ignoreGuestId = null): ?array
    {
        $normalizedName = Guest::normalizeName($name);
        $normalizedPhone = Guest::normalizePhone($phone);

        $query = $this->guests();
        if ($ignoreGuestId) {
            $query->where('id', '!=', $ignoreGuestId);
        }

        $existingGuests = $query->get(['id', 'name', 'phone']);

        $duplicateByName = null;
        $duplicateByPhone = null;

        foreach ($existingGuests as $existing) {
            if ($normalizedName !== '' && Guest::normalizeName($existing->name) === $normalizedName) {
                $duplicateByName = $existing;
            }

            if ($normalizedPhone && Guest::normalizePhone($existing->phone) === $normalizedPhone) {
                $duplicateByPhone = $existing;
            }
        }

        if ($duplicateByName && $duplicateByPhone) {
            return [
                'type' => 'both',
                'message' => 'Você já confirmou sua presença neste evento!',
                'guest' => $duplicateByName,
            ];
        }

        if ($duplicateByName) {
            return [
                'type' => 'name',
                'message' => 'Já existe uma confirmação de presença com este nome completo neste evento.',
                'guest' => $duplicateByName,
            ];
        }

        if ($duplicateByPhone) {
            return [
                'type' => 'phone',
                'message' => 'Já existe uma confirmação de presença com este número de telefone neste evento.',
                'guest' => $duplicateByPhone,
            ];
        }

        return null;
    }
}
