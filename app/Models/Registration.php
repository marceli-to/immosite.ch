<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'interests',
        'first_name',
        'last_name',
        'street',
        'zip_city',
        'email',
        'phone',
        'message',
        'exported_at',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'exported_at' => 'datetime',
        ];
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /** Beschriftungen der gewaehlten Optionen, fuer Mails und Export. */
    public function interestLabels(): array
    {
        $labels = config('registration.interests', []);

        return array_map(fn ($value) => $labels[$value] ?? $value, $this->interests ?? []);
    }
}
