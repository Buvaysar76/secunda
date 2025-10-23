<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use Override;
use MoonShine\UI\Fields\Text;

class RowNumber extends Text
{
    protected static int $globalCounter = 0;

    protected ?int $localCounter = null;

    public function __construct(?string $label = null, ?string $column = null)
    {
        parent::__construct($label ?? '#', $column);
        $this->sortable = false; // Порядковый номер не сортируется
    }

    #[Override]
    protected function resolvePreview(): string
    {
        // Получаем номер страницы и размер страницы для пагинации
        $page = request()->integer('page', 1);
        $perPage = request()->integer('per_page', 25);

        if ($this->localCounter === null) {
            $this->localCounter = ++self::$globalCounter;
        }

        $number = ($page - 1) * $perPage + $this->localCounter;

        return (string)$number;
    }

    public static function resetCounter(): void
    {
        self::$globalCounter = 0;
    }
}
