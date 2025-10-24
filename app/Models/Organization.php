<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'Organization', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'name', type: 'string', example: 'ООО Рога и Копыта'),
    new Property(property: 'building', ref: '#/components/schemas/Building'),
    new Property(property: 'phones', type: 'array', items: new Items(ref: '#/components/schemas/Phone')),
    new Property(property: 'activities', type: 'array', items: new Items(ref: '#/components/schemas/Activity')),
    new Property(property: 'created_at', type: 'string', format: 'date-time'),
    new Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
class Organization extends Model
{
    protected $fillable = [
        'name',
        'building_id',
    ];

    /**
     * @return BelongsTo<Building, $this>
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * @return HasMany<OrganizationPhone, $this>
     */
    public function phones(): HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    /**
     * @return BelongsToMany<Activity, $this>
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activities');
    }
}
