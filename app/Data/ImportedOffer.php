<?php

namespace App\Data;

use App\Models\Offer;
use App\Models\Property;

final readonly class ImportedOffer
{
    public function __construct(
        public Offer $offer,
        public Property $property,
    ) {}
}
