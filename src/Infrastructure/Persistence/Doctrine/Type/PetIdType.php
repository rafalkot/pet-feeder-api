<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\PetId;

final class PetIdType extends AbstractUuidType
{
    const NAME = 'pet_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return PetId::class;
    }
}
