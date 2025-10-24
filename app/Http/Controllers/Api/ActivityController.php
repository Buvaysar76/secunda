<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with([
            'children' => function ($query) {
                $query->where('level', '<=', 3);
            },
            'children.children' => function ($query) {
                $query->where('level', '<=', 3);
            },
            'children.children.children' => function ($query) {
                $query->where('level', '<=', 3);
            }
        ])->whereNull('parent_id')->get();

        return ActivityResource::collection($activities);
    }
}
