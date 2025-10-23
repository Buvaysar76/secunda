<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Activity;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Activity, IndexPage, FormPage, DetailPage>
 */
class ActivityResource extends ModelResource
{
    protected string $model = Activity::class;

    protected array $with = ['parent'];

    public function getTitle(): string
    {
        return 'Деятельности';
    }

    public function indexFields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),
            Text::make('Название', 'name'),
            BelongsTo::make('Родитель', 'parent', formatted: 'name', resource: ActivityResource::class)
                ->nullable(),
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
            'parent_id' => ['int', 'nullable'],
        ];
    }
}
