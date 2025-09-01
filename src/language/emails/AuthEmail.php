<?php

declare(strict_types=1);

namespace Src\language\emails;

use Src\language\Language as L;

enum AuthEmail
{
    case VerificationEmailSubject;
    case VerificationEmailBody;
    case AuthCodeEmailSubject;
    case AuthCodeEmailBody;

    /**
     * @param L|null $lang
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function translate(?L $lang = null, ?array $data = null): string
    {
        $lang ??= L::current();

        if ($data !== null) {
            $this->validateData($data);
        }

        return match ($this) {
            self::VerificationEmailSubject => match ($lang) {
                L::NL => "Verifieer je magicIPTV account.",
                L::EN => "Verify your magicIPTV account.",
                L::DE => "Bestätigen Sie Ihr magicIPTV Konto.",
                L::ES => "Verifica tu cuenta de magicIPTV.",
                L::FR => "Vérifiez votre compte magicIPTV.",
            },
            self::VerificationEmailBody => match ($lang) {
                L::NL => "Hierbij de link om je account te verifieeren, <a href='{$data['link']}'>verifieeren</a>",
                L::EN => "Here is the link to verify your account, <a href='{$data['link']}'>verify</a>",
                L::DE => "Hier ist der Link zur Verifizierung Ihres Kontos: <a href='{$data['link']}'>verifizieren</a>",
                L::ES => "Aquí está el enlace para verificar su cuenta, <a href='{$data['link']}'>verificar</a>",
                L::FR => "Voici le lien pour vérifier votre compte, <a href='{$data['link']}'>vérifier</a>"
            },
            self::AuthCodeEmailSubject => match ($lang) {
                L::NL => "Hier is je MagicIPTV authenticatie code.",
                L::EN => "Here is your MagicIPTV authentication code.",
                L::DE => "Hier ist Ihr MagicIPTV-Authentifizierungscode.",
                L::ES => "Aquí está tu código de autenticación de MagicIPTV.",
                L::FR => "Voici votre code d'authentification MagicIPTV."
            },
            self::AuthCodeEmailBody => match ($lang) {
                L::NL => "Gebruik deze code voor de authenticatie van je account: <b>{$data['auth_code']}</b>",
                L::EN => "Use this code to authenticate your account: <b>{$data['auth_code']}</b>",
                L::DE => "Verwenden Sie diesen Code, um Ihr Konto zu authentifizieren: <b>{$data['auth_code']}</b>",
                L::ES => "Usa este código para autenticar tu cuenta: <b>{$data['auth_code']}</b>",
                L::FR => "Utilisez ce code pour authentifier votre compte : <b>{$data['auth_code']}</b>"
            },
        };
    }

    /**
     * @throws \Exception
     */
    private function validateData(array $data): void
    {
        if ($this === self::VerificationEmailBody && !isset($data['link'])) {
            throw new \Exception("link must be set for verification email body.");
        }
        if ($this === self::AuthCodeEmailBody && !isset($data['auth_code'])) {
            throw new \Exception("auth_code must be set for verification email body.");
        }
    }
}