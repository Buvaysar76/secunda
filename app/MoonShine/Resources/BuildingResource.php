<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Override;
use App\Models\Building;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Building, IndexPage, FormPage, DetailPage>
 */
class BuildingResource extends ModelResource
{
    protected string $model = Building::class;

    #[Override]
    public function getTitle(): string
    {
        return 'Здания';
    }

    public function indexFields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),
            Text::make('Адрес', 'address'),
            Number::make('Широта', 'latitude'),
            Number::make('Долгота', 'longitude'),
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
            Text::make('Адрес', 'address'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'address' => ['string', 'required'],
            'latitude' => ['numeric', 'required'],
            'longitude' => ['numeric', 'required'],
        ];
    }
}
