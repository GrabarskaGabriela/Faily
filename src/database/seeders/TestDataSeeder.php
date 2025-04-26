<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::create([
            'name' => 'Bodzio',
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'email' => 'jan@example.com',
            'password' => Hash::make('password'),
            'age' => '19',
            'phone' => '+48 777 777 777',
            'description' => 'testowe konto',
        ]);

        $userId = $user->id;

        $event = Event::create([
            'user_id' => $userId,
            'title' => 'Koncert w Krakowie',
            'description' => 'Świetny koncert rockowy w centrum Krakowa',
            'date' => now()->addDays(10),
            'latitude' => 50.0647,
            'longitude' => 19.9450,
            'location_name' => 'Arena Kraków',
            'has_ride_sharing' => true,
        ]);

        if (!$event->id) {
            throw new \Exception("Nie udało się utworzyć wydarzenia!");
        }

        $ride = Ride::create([
            'event_id' => $event->id,
            'driver_id' => $userId,
            'vehicle_description' => 'Czerwony Volkswagen Golf',
            'available_seats' => 3,
            'meeting_latitude' => 50.0600,
            'meeting_longitude' => 19.9400,
            'meeting_location_name' => 'Stacja benzynowa BP, ul. Konopnickiej',
        ]);
    }
}
