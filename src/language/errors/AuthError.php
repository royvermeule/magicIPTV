<?php

namespace Src\language\errors;

use Src\language\Language as L;

enum AuthError
{
    case InvalidEmail;
    case InvalidPassword;
    case PasswordDoNotMatch;
    case UserNotFound;
    case UserAlreadyExists;
    case InvalidRegistrationToken;
    case UserNotVerified;

    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::InvalidEmail => match ($lang) {
                L::NL => 'Het opgegeven e-mailadres is onjuist.',
                L::ES => 'El correo electrónico proporcionado no es válido.',
                L::DE => 'Die angegebene E-Mail ist ungültig.',
                L::FR => "L'adresse e-mail fournie est invalide.",
                L::EN => 'The given email is invalid.',
            },
            self::InvalidPassword => match ($lang) {
                L::NL => 'Het wachtwoord voldoet niet aan de eisen.',
                L::ES => 'La contraseña no cumple con los requisitos.',
                L::DE => 'Das Passwort entspricht nicht den Anforderungen.',
                L::FR => 'Le mot de passe ne respecte pas les exigences.',
                L::EN => 'The password does not conform to our standard.',
            },
            self::PasswordDoNotMatch => match ($lang) {
                L::EN => 'Passwords do not match.',
                L::NL => 'Wachtwoorden komen niet overeen.',
                L::FR => "Les mots de passe ne correspondent pas.",
                L::DE => 'Die Passwörter stimmen nicht überein.',
                L::ES => 'Las contraseñas no coinciden.'
            },
            self::UserNotFound => match ($lang) {
                L::NL => 'Deze gebruiker bestaat niet.',
                L::EN => 'The user was not found.',
                L::DE => 'Der Benutzer wurde nicht gefunden.',
                L::ES => 'El usuario no es un usuario.',
                L::FR => "L'utilisateur n'a pas été trouvé."
            },
            self::UserAlreadyExists => match ($lang) {
                L::NL => 'Deze gebruiker bestaat al.',
                L::EN => 'This user already exists.',
                L::DE => 'Dieser Benutzer existiert bereits.',
                L::ES => 'Este usuario ya existe.',
                L::FR => "Cet utilisateur existe déjà."
            },
            self::InvalidRegistrationToken => match ($lang) {
                L::NL => 'Kan de registratie niet vinden.',
                L::EN => "Can't find this registration.",
                L::DE => "Diese Registrierung kann nicht gefunden werden.",
                L::ES => "No puedo encontrar este registro.",
                L::FR => "Je ne trouve pas cette inscription.",
            },
            self::UserNotVerified => match ($lang) {
              L::NL => "Je account is nog niet geverifieerd, check je email voor de verificatie link.",
              L::EN => "Your account is not yet verified, check your email for a verification link.",
              L::DE => "Ihr Konto ist noch nicht verifiziert. Suchen Sie in Ihrem E-Mail-Postfach nach einem Verifizierungslink.",
              L::ES => "Su cuenta aún no está verificada, revise su correo electrónico para obtener un enlace de verificación.",
              L::FR => "Votre compte n'est pas encore vérifié, vérifiez votre e-mail pour un lien de vérification.",
            },
        };
    }
}
