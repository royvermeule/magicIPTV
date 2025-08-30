<?php

declare(strict_types=1);

namespace Src\language\forms\buttons;

use Src\language\Language as L;

enum AuthButton
{
    case LoginButton;
    case RegisterButton;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();
        return match ($this) {
            self::LoginButton => match ($lang) {
                L::NL => 'Inloggen',
                L::EN => 'Log in',
                L::DE => "Annmelden",
                L::ES => "Iniciar sesión",
                L::FR => "Se connecter",
            },
            self::RegisterButton => match ($lang) {
                L::NL => 'Registreren',
                L::EN => 'Sign up',
                L::DE => 'Registrieren',
                L::ES => 'Registrarse',
                L::FR => "S'inscrire",
            },
        };
    }
}
