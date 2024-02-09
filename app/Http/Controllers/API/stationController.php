<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\station;

class stationController extends Controller
{
    public function station(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'station_name' => 'required|string|max:255',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if($validator->fails()){

            return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
        }else{

            $station = station::create([
            'station_name' => $request->station_name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            ]);

            if($station){

                return response()->json([
                    'status' => 201,
                    'station'=>$station,
                    'message'=> "Station added successfully"
                ], 201);
            }else{
                return response()->json([
                    'status' => 500,
                    'message'=> "Something went wrong"
                ], 500);
            }

        }

    }


    public function index()
    {
        $stations = station::orderBy('id')->get();
        return response()->json([
            'status' => 201,
            'stations' => $stations,
        ], 201);
    }

}
