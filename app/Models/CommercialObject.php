<?php

namespace App\Models;

use App\Apartments\Apartment;
use Illuminate\Database\Eloquent\Model;

class CommercialObject extends Model
{
    protected $fillable = ['ref', 'position', 'house', 'floor', 'area', 'rent_net', 'charges', 'status', 'plan_url', 'updated_by'];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'area' => 'float',
            'rent_net' => 'integer',
            'charges' => 'integer',
        ];
    }

    public function toApartment(): Apartment
    {
        return Apartment::fromArray([
            ...$this->only(['ref', 'house', 'floor', 'area', 'rent_net', 'charges', 'status', 'plan_url']),
            'rent_gross' => $this->rent_net !== null && $this->charges !== null ? $this->rent_net + $this->charges : null,
            'type' => 'commercial',
        ]);
    }
}
