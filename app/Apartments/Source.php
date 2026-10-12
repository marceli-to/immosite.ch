<?php

namespace App\Apartments;

interface Source
{
    /** @return array<int, Apartment> in source order */
    public function all(): array;
}
