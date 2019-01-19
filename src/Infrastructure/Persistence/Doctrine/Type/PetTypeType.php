<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\PetType;

final class PetTypeType extends AbstractEnumType
{
    protected $enumClass = PetType::class;

    public function getName()
    {
        return 'pet_type';
    }
}
