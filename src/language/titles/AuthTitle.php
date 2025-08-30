<?php

declare(strict_types = 1);

namespace Src\language\titles;

use Src\language\Language as L;

enum AuthTitle
{
    case LoginTitle;
    case RegisterTitle;
    case ForgetPasswordTitle;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::LoginTitle => match ($lang) {
                L::NL => "Inloggen",
                L::EN => "Log in",
                L::DE => "Anmelden",
                L::ES => "Iniciar sesión",
                L::FR => "Se connecter",
            },
            self::RegisterTitle => match ($lang) {
                L::NL => "Registreren",
                L::EN => "Sign up",
                L::DE => "Registrieren",
                L::ES => "Registrarse",
                L::FR => "S'inscrire",
            },
            self::ForgetPasswordTitle => match ($lang) {
                L::NL => "Wachtwoord vergeten",
                L::EN => "Forgot password",
                L::DE => "Passwort vergessen",
                L::ES => "¿Olvidaste tu contraseña?",
                L::FR => "Mot de passe oublié",
            },
        };
    }
}
