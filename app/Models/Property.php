<?php

namespace App\Models;

use App\Models\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory, HasPublicUuid;

    protected $fillable = [
        'code',
        'name',
        'city',
    ];

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
