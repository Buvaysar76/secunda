<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Organization;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\RelationRepeater;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Link;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Override;

/**
 * @extends ModelResource<Organization, IndexPage, FormPage, DetailPage>
 */
class OrganizationResource extends ModelResource
{
    protected string $model = Organization::class;

    /** @var list<string> */
    protected array $with = ['building', 'phones', 'activities'];

    protected bool $columnSelection = true;

    #[Override]
    public function getTitle(): string
    {
        return 'Организации';
    }

    public function indexFields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),
            Text::make('Название', 'name'),
            RelationRepeater::make('Телефоны', 'phones', resource: OrganizationPhoneResource::class)
                ->removable(),
            BelongsTo::make('Здание', 'building', formatted: 'address', resource: BuildingResource::class),
            BelongsToMany::make('Деятельности', 'activities', formatted: 'name', resource: ActivityResource::class)
                ->tree('parent_id')
                ->inLine(
                    separator: ' ',
                    badge: fn($model, $value) => Badge::make((string) $value, 'primary'),
                    link: fn($model, $value, $field): Link => Link::make(
                        app(ActivityResource::class)->getDetailPageUrl($model->id),
                        $value
                    )
                ),
        ];
    }

    public function formFields(): iterable
    {
        return [
            Box::make([
                ...$this->indexFields(),
            ]),
        ];
    }

    public function detailFields(): iterable
    {
        return [
            ...$this->indexFields(),
        ];
    }

    public function filters(): iterable
    {
        return [
            Text::make('Название', 'name'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'name' => ['string', 'required'],
            'building_id' => ['int', 'required'],
        ];
    }
}
