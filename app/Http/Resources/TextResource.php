<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Override;

/**
 * Class TextResource
 *
 * @property string $key
 * @property string $value
 */
#[Schema(schema: 'Text', properties: [
    new Property(property: 'key', type: 'string'),
    new Property(property: 'value', type: 'string'),
])]
class TextResource extends JsonResource
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
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
