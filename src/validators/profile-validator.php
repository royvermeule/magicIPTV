<?php

declare(strict_types=1);

use Respect\Validation\Validator as v;

return [
    'name' => v::notEmpty()->alpha(),
    'pass_key' => v::stringType()->length(5, 12),
];