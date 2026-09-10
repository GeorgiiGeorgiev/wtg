<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait HasPublicUuid
{
    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
