<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalRooms = 97;
        $rooms = [];
        for ($i = 1; $i <= $totalRooms; $i++) {
            $floor = ceil($i / 10);
            $room_number = ($floor * 100) + ($i % 10 ?? 10);
            $rooms[] = [
                'room_number' => $room_number,
                'floor' => $floor,
                'is_booked' => false,
            ];
        }

        Room::insert($rooms);
    }
}
