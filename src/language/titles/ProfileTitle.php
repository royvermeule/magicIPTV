<?php

declare(strict_types=1);

namespace Src\language\titles;

use Src\language\Language as L;

enum ProfileTitle
{
    case AddProfile;

    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::AddProfile => match ($lang) {
                L::NL => "Profiel "
            },
        };
    }
}
