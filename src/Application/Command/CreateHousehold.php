<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\HouseholdId;
use App\Domain\PersonId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class CreateHousehold extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public function householdId(): HouseholdId
    {
        return HouseholdId::fromString($this->payload['household_id']);
    }

    public function ownerId(): PersonId
    {
        return PersonId::fromString($this->payload['owner_id']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }
}
