<?php

declare(strict_types=1);

namespace Src\language\forms\inputs;

use Src\language\Language;
use Src\language\Language as L;

enum AuthInput
{
    case Email;
    case Password;
    case PasswordConfirm;
    case AuthCode;
    case PasswordInfo;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null): string
    {
        $lang ??= L::current();

        return match ($this) {
            self::Email => match ($lang) {
                L::NL, L::ES, L::EN, L::DE, L::FR => "E-mail",
            },
            self::Password => match ($lang) {
                L::NL => "Wachtwoord",
                L::EN => "Password",
                L::DE => "Passwort",
                L::ES => "Contraseña",
                L::FR => "Mot de passe",
            },
            self::PasswordConfirm => match ($lang) {
                L::NL => "Wachtwoord bevestigen",
                L::EN => "Confirm password",
                L::DE => "Passwort bestätigen",
                L::ES => "Confirmar Contraseña",
                L::FR => "Confirmez le mot de passe",
            },
            self::AuthCode => match ($lang) {
                L::NL => "Authenticatiecode",
                L::EN => "Authentication code",
                L::DE => "Authentifizierungscode",
                L::ES => "Código de autenticación",
                L::FR => "Code d'authentification",
            },
            self::PasswordInfo => match ($lang) {
                L::NL => "Wachtwoord moet minstens bevatten:
        <p>1 hoofdletter*</p>
        <p>1 speciaal teken*</p>
        <p>1 cijfer*</p>",
                L::EN => "Password must contain at least:
        <p>1 uppercase letter*</p>
        <p>1 special character*</p>
        <p>1 number*</p>",
                L::DE => "Passwort muss mindestens enthalten:
        <p>1 Großbuchstabe*</p>
        <p>1 Sonderzeichen*</p>
        <p>1 Zahl*</p>",
                L::ES => "La contraseña debe contener al menos:
        <p>1 letra mayúscula*</p>
        <p>1 carácter especial*</p>
        <p>1 número*</p>",
                L::FR => "Le mot de passe doit contenir au minimum :
        <p>1 lettre majuscule*</p>
        <p>1 caractère spécial*</p>
        <p>1 chiffre*</p>",
            },
        };
    }
}
