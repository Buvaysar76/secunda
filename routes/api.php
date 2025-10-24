<?php

use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\OrganizationLocationController;
use App\Http\Controllers\Api\TextController;
use App\OpenApi\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::middleware('moonshine.basic')
    ->get('/openapi.json', [SwaggerController::class, 'json'])
    ->name('openapi.json');

Route::middleware(['api.key'])->group(function () {
    Route::prefix('organizations')->group(function () {
        Route::get('/by-building/{buildingId}', [OrganizationController::class, 'byBuilding']);
        Route::get('/by-activity/{activityId}', [OrganizationController::class, 'byActivity']);
        Route::get('/{id}', [OrganizationController::class, 'show']);
        Route::get('/search/by-activity', [OrganizationController::class, 'searchByActivity']);
        Route::get('/search/by-name', [OrganizationController::class, 'searchByName']);
    });

    Route::prefix('organizations/location')->group(function () {
        Route::get('/bounding-box', [OrganizationLocationController::class, 'organizationsByBoundingBox']);
        Route::get('/radius', [OrganizationLocationController::class, 'organizationsByRadius']);
    });
});
