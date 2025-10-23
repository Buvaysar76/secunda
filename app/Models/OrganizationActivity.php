<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationActivity extends Model
{
    protected $fillable = [
        'organization_id',
        'activity_id',
    ];
}
