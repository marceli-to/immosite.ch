<?php

namespace App\Apartments;

enum Status: string
{
    case Free = 'free';
    case Reserved = 'reserved';
    case Rented = 'rented';

    /** Visitor-facing label; configurable because some agencies say "verkauft" instead. */
    public function label(): string
    {
        return config("apartments.status_labels.{$this->value}", $this->value);
    }
}
