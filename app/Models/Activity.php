<?php

declare(strict_types=1);

namespace App\Models;

use Override;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'level',
    ];

    protected $with = ['children'];

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * @return BelongsToMany<Organization, $this>
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_activities');
    }

    #[Override]
    protected static function booted(): void
    {
        static::creating(function (Activity $activity): void {
            $activity->setLevel();
        });

        static::updating(function (Activity $activity): void {
            $activity->setLevel();
        });
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
        } else {
            $this->level = 1;
        }
    }
}
