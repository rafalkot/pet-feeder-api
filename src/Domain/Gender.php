<?php

declare(strict_types=1);

namespace App\Domain;

use MyCLabs\Enum\Enum;

/**
 * Class Gender.
 *
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 */
final class Gender extends Enum
{
    public const MALE = 'm';
    public const FEMALE = 'f';
}
