<?php

declare(strict_types=1);

namespace App\Domain;

final class Invitation
{
    /**
     * @var InvitationId
     */
    private $id;

    /**
     * @var Household
     */
    private $household;

    /**
     * @var PersonId
     */
    private $invitedPersonId;

    public function __construct(Household $household, InvitationId $id, PersonId $personId)
    {
        $this->id = $id;
        $this->invitedPersonId = $personId;
        $this->household = $household;
    }

    public function id(): InvitationId
    {
        return $this->id;
    }

    public function householdId(): HouseholdId
    {
        return $this->householdId;
    }

    public function invitedPersonId(): PersonId
    {
        return $this->invitedPersonId;
    }
}
