<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class OrganizationController extends Controller
{
    #[Get(
        path: '/organizations/by-building/{buildingId}',
        operationId: 'byBuilding',
        description: 'Возвращает все организации, находящиеся в конкретном здании',
        summary: 'Список организаций по зданию',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'buildingId',
        description: 'ID здания',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(response: 404, description: 'Organizations not found')]
    public function byBuilding($buildingId): JsonResponse
    {
        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->where('building_id', $buildingId)
            ->get();

        if ($organizations->isEmpty()) {
            return response()->json(['message' => 'Organizations not found'], 404);
        }

        return response()->json($organizations);
    }

    #[Get(
        path: '/organizations/by-activity/{activityId}',
        operationId: 'byActivity',
        description: 'Возвращает все организации, которые относятся к указанному виду деятельности',
        summary: 'Список организаций по виду деятельности',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'activityId',
        description: 'ID вида деятельности',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(response: 404, description: 'Organizations not found')]
    public function byActivity($activityId): JsonResponse
    {
        $activity = Activity::findOrFail($activityId);

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', fn($query) => $query->where('activities.id', $activity->id))
            ->get();

        if ($organizations->isEmpty()) {
            return response()->json(['message' => 'Organizations not found'], 404);
        }

        return response()->json($organizations);
    }

    #[Get(
        path: '/organizations/{id}',
        operationId: 'showOrganization',
        description: 'Вывод информации об организации по её идентификатору',
        summary: 'Информация об организации',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'id',
        description: 'ID организации',
        in: 'path',
        required: true,
        schema: new Schema(type: 'integer')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(ref: '#/components/schemas/Organization')
    )]
    #[Response(response: 404, description: 'Not Found')]
    public function show($id)
    {
        $organization = Organization::with(['building', 'phones', 'activities'])->findOrFail($id);

        return response()->json($organization);
    }

    #[Get(
        path: '/organizations/search/by-activity',
        operationId: 'searchByActivity',
        description: 'Искать организации по виду деятельности, включая вложенные подвиды',
        summary: 'Поиск организаций по деятельности',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'q',
        description: 'Название вида деятельности',
        in: 'query',
        required: true,
        schema: new Schema(type: 'string')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(response: 400, description: 'Query parameter "q" is required')]
    public function searchByActivity(Request $request): JsonResponse
    {
        $q = $request->query('q');

        if (!$q) {
            return response()->json(['error' => 'Query parameter "q" is required'], 400);
        }

        $activities = Activity::where('name', 'like', "%{$q}%")->get();

        $organizationIds = collect();

        foreach ($activities as $activity) {
            $orgIds = Organization::whereHas('activities', function ($q) use ($activity) {
                $activityIds = $activity->getDescendantIds();
                $q->whereIn('activities.id', $activityIds);
            })->pluck('id');

            $organizationIds = $organizationIds->merge($orgIds);
        }

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereIn('id', $organizationIds->unique())
            ->get();

        return response()->json($organizations);
    }

    #[Get(
        path: '/organizations/search/by-name',
        operationId: 'searchByName',
        description: 'Поиск организации по названию',
        summary: 'Поиск организаций по названию',
        security: [['bearerAuth' => []]],
        tags: ['Organization']
    )]
    #[Parameter(
        name: 'q',
        description: 'Название организации',
        in: 'query',
        required: true,
        schema: new Schema(type: 'string')
    )]
    #[Response(
        response: 200,
        description: 'OK',
        content: new JsonContent(properties: [
            new Property(property: 'data', type: 'array', items: new Items(ref: '#/components/schemas/Organization')),
        ])
    )]
    #[Response(response: 400, description: 'Query parameter "q" is required')]
    public function searchByName(Request $request)
    {
        $q = $request->query('q');

        if (!$q) {
            return response()->json(['error' => 'Query parameter "q" is required'], 400);
        }

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->where('name', 'like', "%{$q}%")
            ->get();

        return response()->json($organizations);
    }
}
