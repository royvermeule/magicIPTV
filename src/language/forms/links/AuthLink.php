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
                L::NL => "Wachtwoord vergeten? <a 
                href='#'
                hx-post='/forgot-password' 
                hx-target='body' 
                hx-vals='js:{ email: document.getElementById(\"email\").value }'>
                Herstel wachtwoord</a>",
                L::EN => "Forgot your password? <a
                href='#'
                hx-post='/forgot-password' 
                hx-target='body' 
                hx-vals='js:{ email: document.getElementById(\"email\").value }'>
                Reset password</a>",
                L::DE => "Passwort vergessen? <a 
                href='#'
                hx-swap='outerHTML'
                hx-post='/forgot-password' 
                hx-target='body' 
                hx-vals='js:{ email: document.getElementById(\"email\").value }'>
                Passwort zurücksetzen</a>",
                L::ES => "¿Olvidaste tu contraseña? <a 
                href='#'
                hx-swap='outerHTML''
                hx-post='/forgot-password' 
                hx-target='body' 
                hx-vals='js:{ email: document.getElementById(\"email\").value }'>
                Restablecer contraseña</a>",
                L::FR => "Mot de passe oublié ? <a 
                href='#'
                hx-swap='outerHTML'
                hx-post='/forgot-password' 
                hx-target='body' 
                hx-vals='js:{ email: document.getElementById(\"email\").value }'>
                Réinitialiser le mot de passe</a>",
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
