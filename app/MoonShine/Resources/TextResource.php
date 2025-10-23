<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Text as TextModel;
use MoonShine\ChangeLog\Components\ChangeLog;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\Layer;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Override;
use VI\MoonShineSpatieMediaLibrary\Fields\MediaLibrary;

/**
 * @extends ModelResource<TextModel, IndexPage, FormPage, DetailPage>
 */
class TextResource extends ModelResource
{
    protected string $model = TextModel::class;

    protected string $title = 'Тексты';

    #[Override]
    protected function onLoad(): void
    {
        $this->getFormPage()?->pushToLayer(
            Layer::BOTTOM,
            ChangeLog::make('Changelog', $this, MoonShineUserResource::class)
        );
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Ключ', 'key'),
            Text::make('Значение', 'value'),
            MediaLibrary::make('Галерея', 'gallery')
                ->multiple(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Ключ', 'key'),
                Text::make('Значение', 'value'),
                MediaLibrary::make('Галерея', 'gallery')
                    ->multiple()
                    ->removable(),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Ключ', 'key'),
            Text::make('Значение', 'value'),
            MediaLibrary::make('Галерея', 'gallery')
                ->multiple(),
        ];
    }

    /**
     * @param  TextModel  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:65535'],
        ];
    }
}
