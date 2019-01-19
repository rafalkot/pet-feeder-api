<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Gender;

final class GenderType extends AbstractEnumType
{
    protected $enumClass = Gender::class;

    public function getName()
    {
        return 'gender';
    }
}
