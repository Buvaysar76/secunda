<?php

namespace App\OpenApi\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\SecurityScheme;
use OpenApi\Attributes\Server;
use OpenApi\Generator;

#[Info(version: '1.0', title: 'Application API')]
#[Server(url: '/api', description: 'current')]
#[SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    description: 'Доступ к API через статический API ключ',
    bearerFormat: 'API_KEY',
    scheme: 'bearer'
)]
class SwaggerController extends Controller
{
    public function json(): JsonResponse
    {
        $dirs = [
            app_path('OpenApi/Controllers'),
            app_path('OpenApi/Attributes'),
            app_path('Http/Controllers'),
            app_path('Http/Resources'),
        ];

        // Генерируем OpenAPI-объект
        $generator = new Generator();
        $openapi = $generator->generate($dirs);

        return response()->json($openapi);
    }
}
