<?php

declare(strict_types=1);

use Respect\Validation\Validator as v;

return [
    'email' => v::email()->notEmpty(),
    'password' => v::stringType()->notEmpty()
        ->length(12, 255)
        ->regex('/[0-9]/u')
        ->regex('/[\p{P}\p{S}]/u'),
];