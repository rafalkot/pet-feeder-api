<?php

declare(strict_types=1);

namespace App\Domain;

use MyCLabs\Enum\Enum;

/**
 * Class PetType.
 *
 * @method static PetType CAT()
 * @method static PetType DOG()
 */
final class PetType extends Enum
{
    public const CAT = 'cat';
    public const DOG = 'dog';
}
