<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Provider;

use App\Domain\HouseholdId;
use App\Domain\PersonId;
use App\Tests\DataFixtures\Constants;
use Faker\Provider\Base;
use Ramsey\Uuid\Uuid;

class UuidProvider extends Base
{
    public function uuid4(string $id = null)
    {
        if (null === $id) {
            return Uuid::uuid4();
        }

        return Uuid::fromString($id);
    }

    public function personId(string $id = null): PersonId
    {
        if (is_numeric($id)) {
            $id = Constants::getValue('PERSON_'.$id.'_ID');
        }

        return $id ? PersonId::fromString($id) : PersonId::generate();
    }

    public function householdId(string $id = null)
    {
        return $id ? HouseholdId::fromString($id) : HouseholdId::generate();
    }
}
