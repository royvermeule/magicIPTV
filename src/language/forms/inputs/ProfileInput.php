<?php

declare(strict_types=1);

namespace Src\language\forms\inputs;

use Src\language\Language;
use Src\language\Language as L;

enum ProfileInput
{
    case ProfileName;
    case M3ULink;
    case Passkey;

    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::ProfileName => match ($lang) {
                L::NL => "Profiel naam",
                L::EN => "Profile name",
                L::DE => "Profilname",
                L::ES => "Nombre de perfil",
                L::FR => "Nom du profil",
            },
            self::M3ULink => match ($lang) {
                L::NL => "M3U-link",
                L::EN => "M3U link",
                L::DE => "M3U-Link",
                L::ES => "Enlace M3U",
                L::FR => "Lien M3U",
            },
            self::Passkey => match ($lang) {
                L::NL => "Sleutel",
                L::EN => "Passkey",
                L::DE => "Zugangsschlüssel",
                L::ES => "Clave",
                L::FR => "Clé",
            },
        };
    }
}
