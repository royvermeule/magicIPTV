<?php

declare(strict_types=1);

namespace Src\language\forms\inputs;

use Src\language\Language as L;

enum AuthInput
{
    case Email;
    case Password;
    case PasswordConfirm;

    /**
     * @throws \Exception
     */
    public function translate(?L $lang = null, ?array $data = null): string
    {
        $lang ??= L::current();

        if ($data !== null) {
            $this->validateData($data);
        }

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
            }
        };
    }

    private function validateData(array $data): void
    {
        return;
    }
}
