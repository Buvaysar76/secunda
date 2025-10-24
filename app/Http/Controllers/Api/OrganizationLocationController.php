<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class OrganizationLocationController extends Controller
{
    #[Get(
        path: '/organizations/location/bounding-box',
        operationId: 'organizationsByBoundingBox',
        description: 'Возвращает все организации, расположенные внутри заданного прямоугольника по координатам',
        summary: 'Список организаций в прямоугольной области',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'lat_min',
        description: 'Минимальная широта прямоугольника',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Parameter(
        name: 'lat_max',
        description: 'Максимальная широта прямоугольника',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Parameter(
        name: 'lng_min',
        description: 'Минимальная долгота прямоугольника',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Parameter(
        name: 'lng_max',
        description: 'Максимальная долгота прямоугольника',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(
        response: 400,
        description: 'Ошибка запроса'
    )]
    public function organizationsByBoundingBox(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $latMin = $request->query('lat_min');
        $latMax = $request->query('lat_max');
        $lngMin = $request->query('lng_min');
        $lngMax = $request->query('lng_max');

        if (!$latMin || !$latMax || !$lngMin || !$lngMax) {
            return response()->json(['error' => 'lat_min, lat_max, lng_min, lng_max обязательны'], 400);
        }

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('building', function ($query) use ($latMin, $latMax, $lngMin, $lngMax) {
                $query->whereBetween('latitude', [$latMin, $latMax])
                    ->whereBetween('longitude', [$lngMin, $lngMax]);
            })
            ->get();

        return OrganizationResource::collection($organizations);
    }

    #[Get(
        path: '/organizations/location/radius',
        operationId: 'organizationsByRadius',
        description: 'Возвращает все организации в заданном радиусе от точки по координатам',
        summary: 'Список организаций в радиусе',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'lat',
        description: 'Широта центра радиуса',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Parameter(
        name: 'lng',
        description: 'Долгота центра радиуса',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Parameter(
        name: 'radius',
        description: 'Радиус поиска в километрах',
        in: 'query',
        required: true,
        schema: new Schema(type: 'number', format: 'float')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(
        response: 400,
        description: 'Ошибка запроса'
    )]
    public function organizationsByRadius(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $radius = $request->query('radius');

        if (!$lat || !$lng || !$radius) {
            return response()->json(['error' => 'lat, lng и radius обязательны'], 400);
        }

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->select('organizations.*')
            ->selectRaw('
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(buildings.latitude)) *
                    cos(radians(buildings.longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(buildings.latitude))
                ) AS distance
            ', [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->get();

        return OrganizationResource::collection($organizations);
    }
}
