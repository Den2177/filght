<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'flight_id' => $this->id,
            'flight_code' => $this->flight_code,
            'from' => [
                'city' => $this->airportFrom->city,
                'airport' => $this->airportFrom->name,
                'iata' => $this->airportFrom->iata,
                'date' => $request->get('date1'),
                'time' => $this->time_from
            ],
            'to' => [
                'city' => $this->airportTo->city,
                'airport' => $this->airportTo->name,
                'iata' => $this->airportTo->iata,
                'date' => $request->get('date2'),
                'time' => $this->time_to
            ],
            'cost' => $this->cost,
        ];
    }
}
