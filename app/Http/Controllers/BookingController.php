<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = validator($data, [
            'flight_from.id' => 'required|numeric',
            'flight_from.date' => 'required|date',
            'flight_back.id' => 'required|numeric',
            'flight_back.date' => 'required|date',
            'passengers.*.first_name' => 'required|string',
            'passengers.*.last_name' => 'required|string',
            'passengers.*.birth_date' => 'required|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return $this->error($validator);
        }

        $booking = Booking::create([
            'flight_from' => $data['flight_from']['id'],
            'flight_back' => $data['flight_back']['id'],
            'date_from' => $data['flight_from']['date'],
            'date_back' => $data['flight_back']['date'],
            'code' => Str::random(5),
        ]);

        return response()->json(
            [
                'data' => [
                    'code' => $booking->code,
                ]
            ], 201
        );
    }
}
