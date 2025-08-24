<?php

declare(strict_types = 1);

namespace Src\language\messages;

use Src\language\Language as L;

enum AuthMessage
{
    case verificationLinkSend;
    case registrationCompleted;

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
            }
        };
    }
}
