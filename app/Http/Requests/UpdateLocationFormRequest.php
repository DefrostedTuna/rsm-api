<?php

namespace App\Http\Requests;

use App\Enums\Amenities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Change this based on the Gate system.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'place_id'                      => 'string',
            'name'                          => 'string',
            'locale'                        => 'string',
            'state'                         => 'string',
            'interstate'                    => 'string',
            'exit'                          => 'string',
            'lat'                           => 'numeric',
            'lng'                           => 'numeric',
            'type'                          => 'string',
            'direction'                     => 'string',
            'status'                        => 'string',
            'condition'                     => 'string',
            'amenities'                     => 'array',
            'amenities.*'                   => [
                'string',
                Rule::in(Amenities::toArray())
            ],
            'parking_duration'              => 'integer',
            'parking_spaces'                => 'array',
            'parking_spaces.car'            => 'numeric',
            'parking_spaces.truck'          => 'numeric',
            'parking_spaces.handicapped'    => 'numeric',
            'cell_service'                  => 'array',
            'cell_service.att'              => 'numeric',
            'cell_service.verizon'          => 'numeric',
            'cell_service.sprint'           => 'numeric',
            'cell_service.tmobile'          => 'numeric',
        ];
    }
}
