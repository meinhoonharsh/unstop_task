<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class AppController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('floor')->orderBy('room_number')->get();
        return view('rooms', compact('rooms'));
    }

    public function book(Request $request)
    {
        $request->validate(['rooms' => 'required|integer|min:1|max:5']);
        $numRooms = $request->input('rooms');
        $availableRooms = Room::where('is_booked', false)->get();

        if ($availableRooms->count() < $numRooms) {
            return redirect()->back()->with('error', 'Not enough rooms available.');
        }

        $bookedRooms = $this->findOptimalRooms($availableRooms, $numRooms);
        foreach ($bookedRooms as $room) {
            $room->update(['is_booked' => true]);
        }
        $bookedRooms = $bookedRooms->pluck('room_number')->toArray();

        return  redirect()->back()-> with(['bookedRooms' => $bookedRooms, 'success' => 'Rooms booked successfully.']);
    }

    private function findOptimalRooms($rooms, $numRooms)
    {
        // Group by floor
        $roomsByFloor = $rooms->groupBy('floor');

        // Check for same-floor availability
        foreach ($roomsByFloor as $floor => $floorRooms) {
            if ($floorRooms->count() >= $numRooms) {
                return $floorRooms->sortBy('room_number')->take($numRooms);
            }
        }

        // If not, calculate minimum travel time across floors
        $combinations = $this->getRoomCombinations($rooms, $numRooms);
        $minTravelTime = PHP_INT_MAX;
        $bestCombo = null;

        foreach ($combinations as $combo) {
            $travelTime = $this->calculateTravelTime($combo);
            if ($travelTime < $minTravelTime) {
                $minTravelTime = $travelTime;
                $bestCombo = $combo;
            }
        }

        return collect($bestCombo);
    }

    private function getRoomCombinations($rooms, $numRooms)
    {
        // Simple combination logic (optimize based on your needs)
        return collect($rooms)->sortBy('floor')->sortBy('room_number')->combinations($numRooms);
    }

    private function calculateTravelTime($rooms)
    {
        $rooms = $rooms->sortBy('floor')->sortBy('room_number')->values();
        $totalTime = 0;

        for ($i = 0; $i < $rooms->count() - 1; $i++) {
            $current = $rooms[$i];
            $next = $rooms[$i + 1];

            if ($current->floor == $next->floor) {
                $totalTime += abs($next->room_number - $current->room_number);
            } else {
                $verticalTime = abs($next->floor - $current->floor) * 2;
                $horizontalTime = abs(($next->room_number % 100) - ($current->room_number % 100));
                $totalTime += $verticalTime + $horizontalTime;
            }
        }

        return $totalTime;
    }

    public function reset()
    {
        Room::query()->update(['is_booked' => false]);
        return redirect()->back()->with('success', 'All rooms reset.');
    }

    public function random()
    {
        $rooms = Room::all();
        $rooms->each(function ($room) {
            $room->update(['is_booked' => rand(0, 1)]);
        });
        return redirect()->back()->with('success', 'Random occupancy generated.');
    }
}