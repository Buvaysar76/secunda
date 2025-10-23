<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MoonShine\ChangeLog\Traits\HasChangeLog;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Text extends Model implements HasMedia
{
    use HasChangeLog;
    use InteractsWithMedia;

    protected $fillable = [
        'key',
        'value',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }
}
