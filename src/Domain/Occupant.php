<?php

declare(strict_types=1);

namespace App\Domain;

final class Occupant
{
    /**
     * @var OccupantId
     */
    private $id;

    /**
     * @var Household
     */
    private $household;

    /**
     * @var PersonId
     */
    private $personId;

    /**
     * @var bool
     */
    private $isOwner = false;

    public function __construct(Household $household, OccupantId $id, PersonId $personId, bool $isOwner = false)
    {
        $this->id = $id;
        $this->household = $household;
        $this->personId = $personId;
        $this->isOwner = $isOwner;
    }

    public function householdId(): HouseholdId
    {
        return $this->household->id();
    }

    public function personId(): PersonId
    {
        return $this->personId;
    }

    public function isOwner(): bool
    {
        return $this->isOwner;
    }
}
