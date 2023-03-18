<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'code' => $this->code,
            'cost' => ($this->flightFrom->cost + $this->flightBack->cost) * $this->passengers->count(),
            'flights' => [
                new FlightResource($this->flightFrom),
                new FlightResource($this->flightBack),
            ],
            'passengers' => PassengerResource::collection($this->passengers),
        ];
    }
}
