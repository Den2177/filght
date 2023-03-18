<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $airports = Airport::query()->where('name', 'like', "%$query%")
            ->orWhere('city', 'like', "%$query%")
            ->orWhere('iata', 'like', "%$query%")->get();

        return response()->json([
            'data' => [
                'items' => $airports,
            ],
        ]);
    }
}
