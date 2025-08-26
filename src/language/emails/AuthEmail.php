<?php

declare(strict_types=1);

namespace Src\language\emails;

use Src\language\Language as L;

enum AuthEmail
{
    case VerificationEmailSubject;
    case VerificationEmailBody;

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
            }
        };
    }

    /**
     * @throws \Exception
     */
    private function validateData(array $data): void
    {
        if (
            $this === self::VerificationEmailBody &&
            !isset($data['link'])
        ) {
            throw new \Exception("Link must be set in data for verification email");
        }
    }
}