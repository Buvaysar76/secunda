<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Override;
use App\Models\OrganizationPhone;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Phone;

/**
 * @extends ModelResource<OrganizationPhone, IndexPage, FormPage, DetailPage>
 */
class OrganizationPhoneResource extends ModelResource
{
    protected string $model = OrganizationPhone::class;

    #[Override]
    public function getTitle(): string
    {
        return 'Телефоны';
    }

    public function indexFields(): iterable
    {
        return [
            ID::make('id')
                ->sortable(),
            Phone::make('Телефон', 'phone')
                ->mask('+7 999 999-99-99'),
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
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'organization_id' => ['int', 'required'],
            'phone' => ['string', 'required'],
        ];
    }
}
