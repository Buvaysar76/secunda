<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

/**
 * Class OrganizationResource
 *
 * @property int $id
 * @property string $name
 * @property string[] $phones
 * @property BuildingResource|null $building
 * @property ActivityResource[] $activities
 */
#[Schema(schema: 'Organization', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'name', type: 'string', example: 'ООО Рога и Копыта'),
    new Property(property: 'phones', type: 'array', items: new Items(type: 'string', example: '+7 926 777-77-77')),
    new Property(property: 'building', ref: '#/components/schemas/Building'),
    new Property(property: 'activities', type: 'array', items: new Items(ref: '#/components/schemas/Activity')),
])]
class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phones' => collect($this->phones)->pluck('phone'),
            'building' => new BuildingResource($this->whenLoaded('building')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}
