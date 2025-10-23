<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\TextResource;
use App\MoonShine\Resources\UserResource;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\MenuManager\MenuItem;
use Override;
use App\MoonShine\Resources\BuildingResource;
use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\ActivityResource;

final class MoonShineLayout extends AppLayout
{
    #[Override]
    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuItem::make('Пользователи', UserResource::class),
            MenuItem::make('Тексты', TextResource::class),
            MenuItem::make('Организации', OrganizationResource::class),
            MenuItem::make('Здания', BuildingResource::class),
            MenuItem::make('Деятельности', ActivityResource::class),
        ];
    }

    #[Override]
    protected function getFooterCopyright(): string
    {
        return 'MoonShine';
    }
}
