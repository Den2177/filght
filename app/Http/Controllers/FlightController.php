<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightResource;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->all();

        $validator = validator($data, [
            'from' => 'required|exists:airports,iata',
            'to' => 'required|exists:airports,iata',
            'date1' => 'required|date_format:Y-m-d',
            'date2' => 'nullable|date_format:Y-m-d',
            'passengers' => 'required|numeric|min:1|max:8',
        ]);

        if ($validator->fails()) {
            return $this->error($validator);
        }

        /*$flightsFromTo = Flight::query()
            ->whereHas('airportFrom', function (Builder $b) use ($request) {
                $b->where('iata', $request->from);
            })
            ->whereHas('airportTo', function (Builder $b) use ($request) {
                $b->where('iata', $request->to);
            })->get();*/

        $airportsFrom = Airport::query()->where('iata', $data['from'])->first();
        $airportsTo = Airport::query()->where('iata', $data['to'])->first();

        $flightsFromTo = Flight::query()
            ->where('from_id', $airportsFrom->id)
            ->where('to_id', $airportsTo->id)
            ->get();

        $flightsToFrom = Flight::query()
            ->where('from_id', $airportsTo->id)
            ->where('to_id', $airportsFrom->id)
            ->get();

        return response()->json(
            [
            'data' => [
                'flights_to' => FlightResource::collection($flightsFromTo),
                'flights_back' => isset($data['date2']) ? FlightResource::collection($flightsToFrom) : [],
                ]
            ]
        );

    }
}
