<?php

declare (strict_types = 1);

namespace Src\language\errors;

use Src\language\Language as L;

enum AuthError
{
    case InvalidEmail;
    case EmailEmpty;
    case EmailNotValidFormat;

    case InvalidPassword;
    case PasswordEmpty;
    case PasswordTooShort;
    case PasswordTooLong;
    case PasswordTooWeak;

    case PasswordDoNotMatch;
    case UserNotFound;
    case UserAlreadyExists;
    case InvalidRegistrationToken;
    case UserNotVerified;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            // ===== EMAIL =====
            self::InvalidEmail => match ($lang) {
                L::NL => 'Het opgegeven e-mailadres is onjuist.',
                L::ES => 'El correo electrónico proporcionado no es válido.',
                L::DE => 'Die angegebene E-Mail ist ungültig.',
                L::FR => "L'adresse e-mail fournie est invalide.",
                L::EN => 'The given email is invalid.',
            },
            self::EmailEmpty => match ($lang) {
                L::NL => 'Het e-mailadres mag niet leeg zijn.',
                L::ES => 'El correo electrónico no puede estar vacío.',
                L::DE => 'Die E-Mail darf nicht leer sein.',
                L::FR => "L'adresse e-mail ne peut pas être vide.",
                L::EN => 'The email cannot be empty.',
            },
            self::EmailNotValidFormat => match ($lang) {
                L::NL => 'Het e-mailadres heeft een ongeldig formaat.',
                L::ES => 'El formato del correo electrónico no es válido.',
                L::DE => 'Das E-Mail-Format ist ungültig.',
                L::FR => "Le format de l'adresse e-mail est invalide.",
                L::EN => 'The email format is not valid.',
            },

            // ===== PASSWORD =====
            self::InvalidPassword => match ($lang) {
                L::NL => 'Het wachtwoord voldoet niet aan de eisen.',
                L::ES => 'La contraseña no cumple con los requisitos.',
                L::DE => 'Das Passwort entspricht nicht den Anforderungen.',
                L::FR => 'Le mot de passe ne respecte pas les exigences.',
                L::EN => 'The password does not conform to our standard.',
            },
            self::PasswordEmpty => match ($lang) {
                L::NL => 'Het wachtwoord mag niet leeg zijn.',
                L::ES => 'La contraseña no puede estar vacía.',
                L::DE => 'Das Passwort darf nicht leer sein.',
                L::FR => 'Le mot de passe ne peut pas être vide.',
                L::EN => 'The password cannot be empty.',
            },
            self::PasswordTooShort => match ($lang) {
                L::NL => 'Het wachtwoord is te kort.',
                L::ES => 'La contraseña es demasiado corta.',
                L::DE => 'Das Passwort ist zu kurz.',
                L::FR => 'Le mot de passe est trop court.',
                L::EN => 'The password is too short.',
            },
            self::PasswordTooLong => match ($lang) {
                L::NL => 'Het wachtwoord is te lang.',
                L::ES => 'La contraseña es demasiado larga.',
                L::DE => 'Das Passwort ist zu lang.',
                L::FR => 'Le mot de passe est trop long.',
                L::EN => 'The password is too long.',
            },
            self::PasswordTooWeak => match ($lang) {
                L::NL => 'Het wachtwoord is te zwak.',
                L::ES => 'La contraseña es demasiado débil.',
                L::DE => 'Das Passwort ist zu schwach.',
                L::FR => 'Le mot de passe est trop faible.',
                L::EN => 'The password is too weak.',
            },

            // ===== OTHER ERRORS =====
            self::PasswordDoNotMatch => match ($lang) {
                L::EN => 'Passwords do not match.',
                L::NL => 'Wachtwoorden komen niet overeen.',
                L::FR => "Les mots de passe ne correspondent pas.",
                L::DE => 'Die Passwörter stimmen nicht überein.',
                L::ES => 'Las contraseñas no coinciden.',
            },
            self::UserNotFound => match ($lang) {
                L::NL => 'Deze gebruiker bestaat niet.',
                L::EN => 'The user was not found.',
                L::DE => 'Der Benutzer wurde nicht gefunden.',
                L::ES => 'El usuario no existe.',
                L::FR => "L'utilisateur n'a pas été trouvé.",
            },
            self::UserAlreadyExists => match ($lang) {
                L::NL => 'Deze gebruiker bestaat al.',
                L::EN => 'This user already exists.',
                L::DE => 'Dieser Benutzer existiert bereits.',
                L::ES => 'Este usuario ya existe.',
                L::FR => "Cet utilisateur existe déjà.",
            },
            self::InvalidRegistrationToken => match ($lang) {
                L::NL => 'Kan de registratie niet vinden.',
                L::EN => "Can't find this registration.",
                L::DE => "Diese Registrierung kann nicht gefunden werden.",
                L::ES => "No se puede encontrar este registro.",
                L::FR => "Impossible de trouver cette inscription.",
            },
            self::UserNotVerified => match ($lang) {
                L::NL => "Je account is nog niet geverifieerd, check je email voor de verificatie link.",
                L::EN => "Your account is not yet verified, check your email for a verification link.",
                L::DE => "Ihr Konto ist noch nicht verifiziert. Überprüfen Sie Ihr E-Mail-Postfach auf einen Verifizierungslink.",
                L::ES => "Su cuenta aún no está verificada, revise su correo electrónico para obtener un enlace de verificación.",
                L::FR => "Votre compte n'est pas encore vérifié, vérifiez votre e-mail pour un lien de vérification.",
            },
        };
    }
}
