<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'Activity', properties: [
    new Property(property: 'id', type: 'integer', example: 1),
    new Property(property: 'name', type: 'string', example: 'Еда'),
    new Property(property: 'parent_id', type: 'integer', example: null),
    new Property(property: 'created_at', type: 'string', format: 'date-time'),
    new Property(property: 'updated_at', type: 'string', format: 'date-time'),
])]
class Activity extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Activity $activity) {
            $activity->setLevel();
        });

        static::updating(function (Activity $activity) {
            $activity->setLevel();
        });
    }

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * @return BelongsToMany<Organization, $this>
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_activities');
    }

    /**
     * @throws Exception
     */
    public function setLevel(): void
    {
        if ($this->parent_id) {
            $parent = self::find($this->parent_id);

            if (!$parent) {
                $this->level = 1;

                return;
            }

            $calculatedLevel = $parent->level + 1;

            if ($calculatedLevel > 3) {
                throw new Exception('Нельзя создавать вложенность более 3 уровней');
            }

            $this->level = $calculatedLevel;
        }
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function getDescendants($maxLevel = 3)
    {
        $descendants = collect();
        $currentLevel = $this->level;

        if ($currentLevel < $maxLevel) {
            foreach ($this->children as $child) {
                $descendants->push($child);
                $descendants = $descendants->merge($child->getDescendants($maxLevel));
            }
        }

        return $descendants;
    }

    public function getDescendantIds($maxLevel = 3)
    {
        return $this->getDescendants($maxLevel)->pluck('id')->push($this->id);
    }
}
