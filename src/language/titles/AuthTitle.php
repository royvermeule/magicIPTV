<?php

declare(strict_types = 1);

namespace Src\language\titles;

use Src\language\Language as L;

enum AuthTitle
{
    case LoginTitle;
    case RegisterTitle;
    case ForgetPasswordTitle;
    case VerifyAuthCodeTitle;

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
            self::VerifyAuthCodeTitle => match ($lang) {
                L::NL => "Authenticatiecode invoeren",
                L::EN => "Enter authentication code",
                L::DE => "Authentifizierungscode eingeben",
                L::ES => "Introduce el código de autenticación",
                L::FR => "Saisir le code d'authentification",
            },
        };
    }
}
