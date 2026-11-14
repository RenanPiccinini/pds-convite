<?php

namespace Database\Seeders;

use App\Models\Companion;
use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@pds.com'],
            [
                'name' => 'Renan Piccinini (PDS)',
                'password' => Hash::make('password'),
            ]
        );

        $event = Event::updateOrCreate(
            ['slug' => '96c5a861'],
            [
                'user_id' => $user->id,
                'title' => 'Confraternização Irigaray 2026',
                'description' => 'Confraternização anual comemorativa da Irigaray. Venha celebrar conosco!',
                'event_date' => '2026-12-11 18:30:00',
                'rsvp_deadline' => '2026-11-25 23:55:00',
                'location_name' => 'Espaço Irigaray',
                'location_address' => 'Rua Edu Chaves, 453 - São João, Porto Alegre - RS, 90240-620',
                'location_map_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode('Rua Edu Chaves, 453 - São João, Porto Alegre - RS, 90240-620'),
                'allow_companions' => true,
                'max_companions' => 10,
                'status' => 'active',
                'is_unlimited' => true,
            ]
        );

        // Seed Guest matching screenshot 4
        $guest1 = Guest::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Jessica Aleixo'],
            [
                'phone' => '(51) 98765-4321',
                'confirmed_at' => now()->subHours(4),
                'ip_address' => '127.0.0.1',
            ]
        );

        Companion::firstOrCreate([
            'guest_id' => $guest1->id,
            'name' => 'Lucas Aleixo',
        ]);

        // Second guest
        $guest2 = Guest::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Rodrigo Martins'],
            [
                'phone' => '(51) 99123-9876',
                'confirmed_at' => now()->subHours(8),
                'ip_address' => '127.0.0.1',
            ]
        );

        Companion::firstOrCreate([
            'guest_id' => $guest2->id,
            'name' => 'Camila Silveira',
        ]);

        Companion::firstOrCreate([
            'guest_id' => $guest2->id,
            'name' => 'Bernardo Silveira',
        ]);

        // Third guest
        Guest::updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Mariana Albuquerque'],
            [
                'phone' => '(51) 99888-1122',
                'confirmed_at' => now()->subDay(),
                'ip_address' => '127.0.0.1',
            ]
        );
    }
}
