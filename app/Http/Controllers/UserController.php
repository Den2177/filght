<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Passenger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getBookings()
    {
        $passengers = Passenger::where('document_number', auth()->user()->document_number)->get();
        $bookings = $passengers->map(fn($p) => $p->booking);

        return response()->json(
            [
                'data' => [
                    'items' => BookingResource::collection($bookings),
                ]
            ]
        );
    }

    public function index()
    {
        $user = auth()->user();

        return response()->json(
            [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'document_number' => $user->document_number,
            ]
        );
    }
}
