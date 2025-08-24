<?php

declare(strict_types=1);

namespace Src\language;

use Src\core\Session;

enum Language: string
{
    case EN = 'en';
    case NL = 'nl';
    case ES = 'es';
    case DE = 'de';
    case FR = 'fr';

    public static function current(): self
    {
        /** @var string $lang */
        $lang = Session::get('language') ?? 'en';
        return self::tryFrom($lang) ?? self::EN;
    }
}
