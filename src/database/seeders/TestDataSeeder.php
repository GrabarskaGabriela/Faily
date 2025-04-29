<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Ride;
use App\Models\RideRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TestDataSeeder extends Seeder
{
    /**
     * To jest seeder udający factory XD
     */
    public function run(): void
    {
        $faker = Faker::create('pl_PL');

        $mainUser = User::first() ?? User::create([
            'name' => 'Bodzio',
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'email' => 'jan@example.com',
            'password' => Hash::make('password'),
            'age' => '19',
            'phone' => '+48 777 777 777',
            'description' => 'testowe konto',
        ]);

        $users = [$mainUser];
        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'name' => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'age' => $faker->numberBetween(18, 60),
                'phone' => $faker->phoneNumber,
                'description' => $faker->sentence(10),
            ]);
            $users[] = $user;
        }

        $mainEvent = Event::create([
            'user_id' => $mainUser->id,
            'title' => 'Koncert w Krakowie',
            'description' => 'Świetny koncert rockowy w centrum Krakowa',
            'date' => now()->addDays(10),
            'latitude' => 50.0647,
            'longitude' => 19.9450,
            'location_name' => 'Arena Kraków',
            'has_ride_sharing' => true,
        ]);

        if (!$mainEvent->id) {
            throw new \Exception("Nie udało się utworzyć wydarzenia!");
        }

        // Kilka dodatkowych wydarzeń
        $cities = [
            ['name' => 'Warszawa', 'lat' => 52.229676, 'lng' => 21.012229],
            ['name' => 'Gdańsk', 'lat' => 54.372158, 'lng' => 18.638306],
            ['name' => 'Wrocław', 'lat' => 51.107885, 'lng' => 17.038538],
            ['name' => 'Poznań', 'lat' => 52.406374, 'lng' => 16.925168],
            ['name' => 'Katowice', 'lat' => 50.270908, 'lng' => 19.039993],
        ];

        $eventTypes = [
            'Koncert', 'Festiwal', 'Mecz', 'Spektakl', 'Konferencja', 'Wystawa',
            'Targi', 'Piknik', 'Warsztat', 'Spotkanie'
        ];

        $events = [$mainEvent];

        for ($i = 0; $i < 10; $i++) {
            $city = $faker->randomElement($cities);
            $eventType = $faker->randomElement($eventTypes);
            $organizerId = $faker->randomElement($users)->id;

            $latOffset = ($faker->randomFloat(6, -0.01, 0.01));
            $lngOffset = ($faker->randomFloat(6, -0.01, 0.01));

            $event = Event::create([
                'user_id' => $organizerId,
                'title' => $eventType . ' w ' . $city['name'],
                'description' => $faker->paragraph,
                'date' => now()->addDays($faker->numberBetween(1, 60)),
                'latitude' => $city['lat'] + $latOffset,
                'longitude' => $city['lng'] + $lngOffset,
                'location_name' => $faker->randomElement(['Arena', 'Stadion', 'Hala', 'Centrum', 'Park']) . ' ' . $city['name'],
                'has_ride_sharing' => $faker->boolean(70), // 70% szans na true
            ]);

            $events[] = $event;
        }

        $mainRide = Ride::create([
            'event_id' => $mainEvent->id,
            'driver_id' => $mainUser->id,
            'vehicle_description' => 'Czerwony Volkswagen Golf',
            'available_seats' => 3,
            'meeting_latitude' => 50.0600,
            'meeting_longitude' => 19.9400,
            'meeting_location_name' => 'Stacja benzynowa BP, ul. Konopnickiej',
        ]);

        $carBrands = ['Toyota', 'Ford', 'Skoda', 'Volkswagen', 'Opel', 'Honda', 'Renault', 'Peugeot'];
        $carModels = ['Corolla', 'Focus', 'Octavia', 'Golf', 'Astra', 'Civic', 'Megane', '308'];
        $carColors = ['Czerwony', 'Niebieski', 'Biały', 'Czarny', 'Srebrny', 'Zielony', 'Żółty', 'Szary'];
        $meetingPoints = ['Stacja benzynowa', 'Parking', 'Przystanek autobusowy', 'Centrum handlowe', 'Kawiarnia'];

        foreach ($events as $event) {
            if ($event->id === $mainEvent->id) {
                continue;
            }

            if ($event->has_ride_sharing) {
                $ridesCount = $faker->numberBetween(1, 3);

                for ($i = 0; $i < $ridesCount; $i++) {
                    $driver = $faker->randomElement($users);
                    $carBrand = $faker->randomElement($carBrands);
                    $carModel = $faker->randomElement($carModels);
                    $carColor = $faker->randomElement($carColors);

                    $latOffset = ($faker->randomFloat(6, -0.008, 0.008));
                    $lngOffset = ($faker->randomFloat(6, -0.008, 0.008));

                    $ride = Ride::create([
                        'event_id' => $event->id,
                        'driver_id' => $driver->id,
                        'vehicle_description' => $carColor . ' ' . $carBrand . ' ' . $carModel,
                        'available_seats' => $faker->numberBetween(1, 6),
                        'meeting_latitude' => $event->latitude + $latOffset,
                        'meeting_longitude' => $event->longitude + $lngOffset,
                        'meeting_location_name' => $faker->randomElement($meetingPoints) . ', ' . $faker->streetName,
                    ]);

                    $passengersCount = $faker->numberBetween(0, $ride->available_seats);
                    $potentialPassengers = array_filter($users, function($user) use ($driver) {
                        return $user->id !== $driver->id;
                    });

                    $selectedPassengers = $faker->randomElements(
                        $potentialPassengers,
                        min($passengersCount, count($potentialPassengers))
                    );

                    foreach ($selectedPassengers as $passenger) {
                        RideRequest::create([
                            'ride_id' => $ride->id,
                            'passenger_id' => $passenger->id,
                            'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                            'message' => $faker->sentence,
                        ]);
                    }
                }
            }
        }
    }
}
