<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\PersonId;

final class OccupantIdType extends AbstractUuidType
{
    const NAME = 'occupant_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return PersonId::class;
    }
}
