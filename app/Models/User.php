<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp_instance',
        'whatsapp_status',
        'whatsapp_phone',
        'whatsapp_connected_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'whatsapp_connected_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isWhatsAppConnected(): bool
    {
        return $this->whatsapp_status === 'connected' && !empty($this->whatsapp_instance);
    }

    public function getOrCreateWhatsAppInstanceName(): string
    {
        if (! $this->whatsapp_instance) {
            $this->whatsapp_instance = 'pds_user_' . $this->id;
            $this->save();
        }

        return $this->whatsapp_instance;
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('event_date', 'asc');
    }
}
