<?php

declare(strict_types = 1);

namespace Src\language\messages;

use Src\language\Language as L;

enum AuthMessage
{
    case verificationLinkSend;
    case registrationCompleted;
    case NoAccountYet;
    case AuthenticationMailSend;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::verificationLinkSend => match ($lang) {
                L::NL => "Verificatie link verstuurd, deze is 60 minuten geldig.",
                L::EN => "Verification link send, you can use it for 60 minutes.",
                L::ES => "Enlace de verificación enviado, puedes usarlo durante 60 minutos.",
                L::FR => "Lien de vérification envoyé, vous pouvez l'utiliser pendant 60 minutes.",
                L::DE => "Bestätigungslink gesendet, Sie können ihn 60 Minuten lang verwenden."
            },
            self::registrationCompleted => match ($lang) {
                L::NL => "Registratie voltooid, ga naar <a href='/login'>inloggen</a>",
                L::EN => "You registered your account successfully, please go to <a href='/login'>login</a>.",
                L::DE => "Sie haben Ihr Konto erfolgreich registriert. Gehen Sie bitte zu <a href='/login'>Anmelden</a>",
                L::ES => "Has registrado tu cuenta exitosamente, por favor ve a <a href='/login'>login</a>",
                L::FR => "Vous avez enregistré votre compte avec succès, veuillez vous rendre sur <a href='/login'>login</a>",
            },
            self::NoAccountYet => match ($lang) {
                L::NL => "<p>Nog geen account? Maak een account aan.</p> <a href='/register' hx-target='body'>Registreren</a>",
                L::EN => "<p>Don't have an account yet? Create it here.</p> <a href='/register' hx-target='body'>Register</a>",
                L::DE => "<p>Kein Konto? Erstellen Sie ein Konto.</p> <a href='/register' hx-target='body'>Registrieren</a>",
                L::ES => "<p>¿Tenías una cuenta? Crea una cuenta.</p> <a href='/register' hx-target='body'>Registrarse</a>",
                L::FR => "<p>Pas de compte ? Créez un compte.</p> <a href='/register' hx-target='body'>S'inscrire</a>",
            },
            self::AuthenticationMailSend => match ($lang) {
                L::NL => "Check je E-mail voor de code, deze is 60 minuten geldig.",
                L::EN => "Check your email for the code, it is valid for 60 minutes.",
                L::DE => "Überprüfen Sie Ihre E-Mail auf den Code, dieser ist 60 Minuten gültig.",
                L::ES => "Revisa tu correo electrónico para el código, es válido por 60 minutos.",
                L::FR => "Vérifiez votre e-mail pour le code, il est valable pendant 60 minutes.",
            }
        };
    }
}
