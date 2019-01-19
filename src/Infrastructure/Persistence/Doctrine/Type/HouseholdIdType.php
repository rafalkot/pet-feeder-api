<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\HouseholdId;

final class HouseholdIdType extends AbstractUuidType
{
    const NAME = 'household_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return HouseholdId::class;
    }
}
