<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\PassengerResource;
use App\Models\Booking;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Mockery\Generator\StringManipulation\Pass\Pass;

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

        $passengers = $data['passengers'];

        foreach ($passengers as $passenger) {
            $creatable = (collect($passenger)->merge(['booking_id' => $booking->id]))->toArray();
            Passenger::create($creatable);
        }

        return response()->json(
            [
                'data' => [
                    'code' => $booking->code,
                ]
            ], 201
        );
    }

    public function getByCode($code)
    {
        $booking = Booking::firstWhere('code', $code);
        return response()->json(
            [
                'data' => new BookingResource($booking),
            ]
        );
    }

    public function getSeatedPlaces($code)
    {
        $booking = Booking::firstWhere('code', $code);

        $occupiedFrom = $booking->passengers->map(fn($p) => [
            'passenger_id' => $p->id,
            'place' => $p->place_from,
        ])->filter(fn($p) => $p['place'] !== null)->values();

        $occupiedBack = $booking->passengers->map(fn($p) => [
            'passenger_id' => $p->id,
            'place' => $p->place_back,
        ])->filter(fn($p) => $p['place'] !== null)->values();

        return response()->json(
            [
                'occupied_from' => $occupiedFrom,
                'occupied_back' => $occupiedBack,
            ]
        );
    }

    public function replaceSeat(Request $request, $code)
    {
        $booking = Booking::firstWhere('code', $code);
        $data = $request->all();

        $validator = validator($data, [
            'passenger' => 'required|exists:passenger,id',
            'seat' => 'required|string',
            'type' => 'required|string',
        ]);

        $ids = $booking->passengers->map(fn($i) => $i->id);

        if (!$ids->contains($data['passenger'])) {
            return response()->json(
                [
                    'error' => [
                        'code' => 403,
                        'message' => 'Passenger does not apply to booking'
                    ]
                ], 403
            );
        }

        $direction = $data['type'];

        $isOccupied = (bool) Passenger::query()->where('place_'.$direction, $data['seat'])->count();

        if ($isOccupied) {
            return response()->json(
                [
                    'error' => [
                        'code' => 422,
                        'message' => 'Seat is occupied',
                    ]
                ], 422
            );
        }

        $passenger = Passenger::find($data['passenger']);
        $passenger->update([
            'place_'.$direction => $data['seat']
        ]);

        return new PassengerResource($passenger);
    }
}
