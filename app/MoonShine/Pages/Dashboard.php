<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\MenuManager\Attributes\SkipMenu;
use Override;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;

#[SkipMenu]
class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    #[Override]
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    #[Override]
    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [];
    }
}
