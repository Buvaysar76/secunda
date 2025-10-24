<?php

namespace App\Http\Resources;

use Override;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

/**
 * Class BuildingResource
 *
 * @property int $id
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 */
#[Schema(schema: 'Building', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'address', type: 'string', example: 'ул. Вишняки, 34'),
    new Property(property: 'latitude', type: 'number', format: 'float', example: 55.7558),
    new Property(property: 'longitude', type: 'number', format: 'float', example: 37.6173),
])]
class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
