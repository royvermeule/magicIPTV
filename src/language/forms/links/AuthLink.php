<?php

declare(strict_types=1);

namespace Src\language\forms\links;

use Src\language\Language as L;

enum AuthLink
{
    case Register;
    case ForgotPassword;
    case Login;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::Register => match ($lang) {
                L::NL => "Heb je nog geen account? <a hx-target='body' href='/register'>Registreren</a>",
                L::EN => "Don't have an account yet? <a hx-target='body' href='/register'>Register</a>",
                L::DE => "Sie haben noch kein Konto? <a hx-target='body' href='/register'>Registrieren</a>",
                L::ES => "¿Aún no tienes una cuenta? <a hx-target='body' href='/register'>Registrarse</a>",
                L::FR => "Vous n'avez pas encore de compte? <a hx-target='body' href='/register'>S'inscrire</a>",
            },
            self::ForgotPassword => match ($lang) {
                L::NL => "Wachtwoord vergeten? <a hx-target='body' href='/forgot-password'>Herstel wachtwoord</a>",
                L::EN => "Forgot your password? <a hx-target='body' href='/forgot-password'>Reset password</a>",
                L::DE => "Passwort vergessen? <a hx-target='body' href='/forgot-password'>Passwort zurücksetzen</a>",
                L::ES => "¿Olvidaste tu contraseña? <a hx-target='body' href='/forgot-password'>Restablecer contraseña</a>",
                L::FR => "Mot de passe oublié ? <a hx-target='body' href='/forgot-password'>Réinitialiser le mot de passe</a>",
            },
            self::Login => match ($lang) {
                L::NL => "Heb je al een account? <a hx-target='body' href='/login'>Inloggen</a>",
                L::EN => "Already have an account? <a hx-target='body' href='/login'>Login</a>",
                L::DE => "Sie haben bereits ein Konto? <a hx-target='body' href='/login'>Anmelden</a>",
                L::ES => "¿Ya tienes una cuenta? <a hx-target='body' href='/login'>Iniciar sesión</a>",
                L::FR => "Vous avez déjà un compte ? <a hx-target='body' href='/login'>Connexion</a>",
            }
        };
    }
}
