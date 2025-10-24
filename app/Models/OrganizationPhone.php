<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'Phone', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'organization_id', type: 'integer', example: 1),
    new Property(property: 'phone', type: 'string', example: '+7 999 123-45-67'),
    new Property(property: 'created_at', type: 'string', format: 'date-time'),
    new Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
class OrganizationPhone extends Model
{
    protected $fillable = [
        'organization_id',
        'phone',
    ];

    /**
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
