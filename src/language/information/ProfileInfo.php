<?php

declare(strict_types=1);

namespace Src\language\information;

use Src\language\Language;
use Src\language\Language as L;

enum ProfileInfo
{
    case NewProfile;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::NewProfile => match ($lang) {
                L::NL => "Nieuw profiel",
                L::EN => "New profile",
                L::DE => "Neues Profil",
                L::ES => "Nuevo perfil",
                L::FR => "Nouveau profil",
            },
        };
    }
}
