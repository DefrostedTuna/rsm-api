<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Location extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'google_place_id' => $this->google_place_id,
            'name' => $this->name,
            'locale' => $this->locale,
            'state' => $this->state,
            'interstate' => $this->interstate,
            'exit' => $this->exit,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'direction' => $this->direction,
            'status' => $this->status,
            'condition' => $this->condition,
            'amenities' => $this->amenities,
            'parking_duration' => $this->parking_duration,
            'parking_spaces' => $this->parking_spaces,
            'cell_service' => $this->cell_service,
            'rating' => $this->whenLoaded('ratings', [
                'avg' => $this->avgRating(),
                'count' => $this->ratings()->count(),                
            ]),
        ];
    }
}
