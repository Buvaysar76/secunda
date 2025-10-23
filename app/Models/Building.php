<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
		'address',
		'latitude',
		'longitude',
    ];
}
