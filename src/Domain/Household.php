<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;

final class Household
{
    /**
     * @var HouseholdId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Occupant[]|ArrayCollection
     */
    private $occupants;

    /**
     * @var Invitation[]|ArrayCollection
     */
    private $invitations;

    private function __construct()
    {
        $this->occupants = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    public static function create(HouseholdId $id, string $name, PersonId $ownerId)
    {
        $home = new self();
        $home->id = $id;
        $home->name = $name;
        $home->occupants->add(new Occupant($home, OccupantId::generate(), $ownerId, true));

        return $home;
    }

    public function addOccupant(PersonId $personId): Occupant
    {
        if (null !== $this->getOccupant($personId)) {
            throw new \DomainException('Occupant with such person id already exists');
        }

        $occupant = new Occupant($this, OccupantId::generate(), $personId, false);
        $this->occupants->add($occupant);

        return $occupant;
    }

    public function id(): HouseholdId
    {
        return $this->id;
    }

    public function getOccupant(PersonId $id): ? Occupant
    {
        $occupant = $this->occupants->filter(
            function (Occupant $occupant) use ($id) {
                return $occupant->personId()->equals($id);
            }
        )->first();

        return $occupant ? $occupant : null;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function invitePerson(Person $person): Invitation
    {
        if (null !== $this->getInvitationByPerson($person->id())) {
            throw new \DomainException('Invitation with such person id already exists');
        }

        $invitation = new Invitation($this, InvitationId::generate(), $person->id());

        $this->invitations->add($invitation);

        return $invitation;
    }

    public function getInvitationByPerson(PersonId $personId): ? Invitation
    {
        $invitation = $this->invitations->filter(
            function (Invitation $invitation) use ($personId) {
                return $invitation->invitedPersonId()->equals($personId);
            }
        )->first();

        return $invitation ? $invitation : null;
    }

    public function acceptInvitation(InvitationId $invitationId): void
    {
        $invitation = $this->getInvitationById($invitationId);
        $this->addOccupant($invitation->invitedPersonId());

        $this->invitations = $this->invitations->filter(function (Invitation $invitation) use ($invitationId) {
            return !$invitation->id()->equals($invitationId);
        });
    }

    public function getInvitationById(InvitationId $invitationId): ? Invitation
    {
        $invitation = $this->invitations->filter(
            function (Invitation $invitation) use ($invitationId) {
                return $invitation->id()->equals($invitationId);
            }
        )->first();

        return $invitation ? $invitation : null;
    }

    /**
     * @return Invitation[]
     */
    public function invitations(): array
    {
        return $this->invitations->toArray();
    }

    public function changeHouseholder(PersonId $id): void
    {
        if (null === $this->getOccupant($id)) {
            throw new \DomainException('Occupant with such person id doesn\'t exist');
        }

        $occupants = $this->occupants();
        $currentHouseholder = $this->householder();

        foreach ($occupants as $idx => $occupant) {
            if ($occupant->isOwner() || $occupant->personId()->equals($id)) {
                unset($occupants[$idx]);
            }
        }

        $newHouseholder = new Occupant($this, OccupantId::generate(), $id, true);
        $newOccupant = new Occupant($this, OccupantId::generate(), $currentHouseholder->personId());

        $occupants[] = $newHouseholder;
        $occupants[] = $newOccupant;

        $this->occupants = new ArrayCollection($occupants);
    }

    public function householder(): Occupant
    {
        $householder = $this->occupants->filter(
            function (Occupant $occupant) {
                return $occupant->isOwner();
            }
        )->first();

        return $householder ? $householder : null;
    }

    /**
     * @return Occupant[]
     */
    public function occupants(): array
    {
        return $this->occupants->toArray();
    }

    public function removeOccupant(PersonId $occupantToRemove)
    {
        $occupant = $this->getOccupant($occupantToRemove);

        if (!$occupant) {
            throw new \DomainException('Occupant with such id does not exist');
        }

        if ($occupant->isOwner()) {
            throw new \DomainException('Householder can\'t be removed');
        }

        $this->occupants->removeElement($occupant);
    }
}
