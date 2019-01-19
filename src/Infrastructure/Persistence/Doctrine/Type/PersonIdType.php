<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\PersonId;

final class PersonIdType extends AbstractUuidType
{
    const NAME = 'person_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return PersonId::class;
    }
}
