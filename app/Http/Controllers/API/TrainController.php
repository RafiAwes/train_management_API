<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'train_id' => 'required|integer',
            'train_name' => 'required|string',
            'capacity' => 'required|integer',
            'stops' => 'required|array',
        ]);

        $train = Train::create($validatedData);

        // Create stops for the train
        foreach ($validatedData['stops'] as $stopData) {
            $train->stops()->create($stopData);
        }

        return response()->json($train, 201);
    }

    public function getByStation(Request $request, $stationId)
    {
        $station = Station::findOrFail($stationId);

        $trains = $station->trains()->orderBy([
            ['departure_time', 'asc'],
            ['arrival_time', 'asc'],
        ])->get();

        return response()->json([
            'station_id' => $station->id,
            'trains' => $trains,
        ]);
    }

}
