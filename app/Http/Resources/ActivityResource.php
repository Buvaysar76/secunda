<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

/**
 * Class ActivityResource
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property Collection|ActivityResource[]|null $children
 */
#[Schema(schema: 'Activity', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'name', type: 'string', example: 'Еда'),
    new Property(property: 'parent_id', type: 'integer', example: null),
    new Property(
        property: 'children',
        type: 'array',
        items: new Items(ref: '#/components/schemas/Activity'),
        nullable: true
    ),
])]
class ActivityResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'children' => ActivityResource::collection($this->children)
        ];
    }
}
