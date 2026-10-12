<?php

namespace App\Apartments;

/**
 * One apartment in the normalised format every source returns, so views and
 * filters never depend on where the data comes from (static config, Flatfox, Melon).
 */
final readonly class Apartment
{
    public function __construct(
        public string $ref,
        public Status $status,
        public ?string $house = null,
        public ?string $floor = null,
        public ?float $rooms = null,
        public ?float $area = null,
        public ?float $outdoorArea = null,
        public ?int $rentNet = null,
        public ?int $charges = null,
        public ?int $rentGross = null,
        public ?int $price = null,
        public ?string $planUrl = null,
        public ?string $applyUrl = null,
        public ?string $availableFrom = null,
        public string $type = 'apartment',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            ref: (string) $data['ref'],
            status: Status::tryFrom($data['status'] ?? '') ?? Status::Free,
            house: self::string($data['house'] ?? null),
            floor: self::string($data['floor'] ?? null),
            rooms: self::number($data['rooms'] ?? null),
            area: self::number($data['area'] ?? null),
            outdoorArea: self::number($data['outdoor_area'] ?? null),
            rentNet: self::integer($data['rent_net'] ?? null),
            charges: self::integer($data['charges'] ?? null),
            rentGross: self::integer($data['rent_gross'] ?? null),
            price: self::integer($data['price'] ?? null),
            planUrl: self::string($data['plan_url'] ?? null),
            applyUrl: self::string($data['apply_url'] ?? null),
            availableFrom: self::string($data['available_from'] ?? null),
            type: self::string($data['type'] ?? null) ?? 'apartment',
        );
    }

    public function toArray(): array
    {
        return [
            'ref' => $this->ref,
            'status' => $this->status->value,
            'house' => $this->house,
            'floor' => $this->floor,
            'rooms' => $this->rooms,
            'area' => $this->area,
            'outdoor_area' => $this->outdoorArea,
            'rent_net' => $this->rentNet,
            'charges' => $this->charges,
            'rent_gross' => $this->rentGross,
            'price' => $this->price,
            'plan_url' => $this->planUrl,
            'apply_url' => $this->applyUrl,
            'available_from' => $this->availableFrom,
            'type' => $this->type,
        ];
    }

    public function isAvailable(): bool
    {
        return $this->status !== Status::Rented;
    }

    /** "3.5" instead of "3.50" or "3.5000", "4" instead of "4.0". */
    public function roomsLabel(): ?string
    {
        return $this->rooms === null ? null : rtrim(rtrim(number_format($this->rooms, 1, '.', ''), '0'), '.');
    }

    private static function string(mixed $value): ?string
    {
        // Melon sends "#" for files that are not uploaded.
        return $value === null || $value === '' || $value === '#' ? null : (string) $value;
    }

    private static function number(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private static function integer(mixed $value): ?int
    {
        return is_numeric($value) ? (int) round((float) $value) : null;
    }
}
