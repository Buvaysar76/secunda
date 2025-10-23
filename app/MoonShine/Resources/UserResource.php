<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\ImportExport\Contracts\HasImportExportContract;
use MoonShine\ImportExport\ExportHandler;
use MoonShine\ImportExport\Traits\ImportExportConcern;
use MoonShine\Laravel\Enums\Action;
use MoonShine\Laravel\Handlers\Handler;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\ToastType;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends ModelResource<User, IndexPage, FormPage, DetailPage>
 */
class UserResource extends ModelResource implements HasImportExportContract
{
    use ImportExportConcern;

    protected string $model = User::class;

    protected string $title = 'Пользователи';

    /**
     * @return list<FieldContract>
     */
    protected function exportFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Name'),
            Email::make('Email'),
        ];
    }

    protected function export(): ?Handler
    {
        return ExportHandler::make(__('moonshine::ui.export'))
            ->queue()
            ->filename(sprintf('export_%s', date('Ymd-His')))
            ->dir('/exports')
            ->notifyUsers(fn(): array => [auth()->id()]);
    }

    protected function import(): ?Handler
    {
        return null;
    }

    protected function activeActions(): ListOf
    {
        return new ListOf(Action::class, [Action::VIEW, Action::DELETE]);
    }

    /**
     * @throws Throwable
     */
    public function sendVerification(MoonShineRequest $request): MoonShineJsonResponse
    {
        /** @var User $user */
        $user = $request->getResource()?->getItem();
        $cacheKey = 'verification_request_'.$user->id;
        $throttleLimit = 6;
        $throttleTime = 60;

        // Проверяем, был ли запрос отправлен за последние 1 минуту
        $attempts = cache()->get($cacheKey, 0);
        assert(is_int($attempts));

        if ($attempts >= $throttleLimit) {
            return MoonShineJsonResponse::make()->toast(
                'Вы превысили лимит запросов. Попробуйте позже.',
                ToastType::ERROR
            );
        }

        if ($user->hasVerifiedEmail()) {
            return MoonShineJsonResponse::make()->toast('Email уже подтвержден', ToastType::ERROR);
        }

        // Увеличиваем количество попыток и сохраняем в кеше
        cache()->put($cacheKey, $attempts + 1, $throttleTime);

        $user->sendEmailVerificationNotification();

        return MoonShineJsonResponse::make()->toast('Письмо подтверждения отправлено', ToastType::SUCCESS);
    }

    /**
     * @throws Throwable
     */
    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->prepend(
                ActionButton::make('Отправить письмо подтверждения')
                    ->method(
                        'sendVerification',
                        fn(mixed $item): array => ['resourceItem' => $item instanceof Model ? $item->getKey() : null]
                    )
                    ->async()
                    ->icon('envelope')
            );
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name'),
            Email::make('Email', 'email'),
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
                Text::make('Имя', 'name'),
                Email::make('Email', 'email'),
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
            Text::make('Имя', 'name'),
            Email::make('Email', 'email'),
        ];
    }

    public function filters(): iterable
    {
        return [
            Text::make('Имя', 'name'),
            Email::make('Email', 'email'),
        ];
    }
}
