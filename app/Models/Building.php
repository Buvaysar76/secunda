<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'Building', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'address', type: 'string', example: 'ул. Вишняки, 34'),
    new Property(property: 'latitude', type: 'number', format: 'float', example: 55.7558),
    new Property(property: 'longitude', type: 'number', format: 'float', example: 37.6173),
    new Property(property: 'created_at', type: 'string', format: 'date-time'),
    new Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
class Building extends Model
{
    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * @return HasMany<Organization, $this>
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
