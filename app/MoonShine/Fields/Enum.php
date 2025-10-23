<?php

namespace App\MoonShine\Fields;

use Override;
use BackedEnum;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Fields\Enum as BaseEnum;

/**
 * Enum поле с поддержкой multiple в preview режиме
 */
class Enum extends BaseEnum
{
    #[Override]
    protected function resolvePreview(): string
    {
        /** @var string|int|iterable<(int|string), string|int>|Collection<int, string|int>|null $value */
        $value = $this->toFormattedValue();

        if (is_null($value)) {
            return '';
        }

        if ($this->isMultiple()) {
            $value = is_string($value) && str($value)->isJson() ?
                json_decode($value, true, 512, JSON_THROW_ON_ERROR)
                : $value;
            if (!is_array($value) && !is_iterable($value) && !$value instanceof Arrayable) {
                $value = [$value];
            }

            /** @var array<int, string|int> $value */
            /** @var Collection<int, string|int> $collection */
            $collection = collect($value);

            if ($this->attached !== null) {
                /** @var Collection<int, BackedEnum> $collection */
                $collection = rescue(fn() => $collection->map($this->attached::tryFrom(...))) ?? $collection;
            }

            return $collection
                ->when(
                    !$this->isRawMode(),
                    fn($collect): Collection => $collect->map(
                        function ($v): string {
                            /**
                             * @var BackedEnum $v
                             * @var string $label
                             */
                            $label = method_exists($v, 'toString') ? $v->toString() : $v->name;
                            if (method_exists($v, 'getColor') && is_string($color = $v->getColor())) {
                                /** @var string $label */
                                $label = Badge::make($label, $color)->render();
                            }

                            return $label;
                        },
                    ),
                )
                ->implode(' ');
        }

        if ($this->attached !== null && !$value instanceof $this->attached && !is_iterable($value)) {
            $value = rescue(fn() => $this->attached::tryFrom($value)) ?? $value;
        }

        if (is_scalar($value)) {
            $result = data_get(
                $this->getValues(),
                $value,
                (string) $value,
            );
            assert(is_string($result));

            return $result;
        }
        /** @var BackedEnum $value */
        if (method_exists($value, 'getColor')) {
            /** @var string|Color|Closure|null $color */
            $color = $value->getColor();
            $this->badge($color);
        }

        if (method_exists($value, 'toString')) {
            $result = $value->toString();
            assert(is_string($result));

            return $result;
        }

        return (string) ($value->value ?? '');
    }
}
