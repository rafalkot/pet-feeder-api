<?php

namespace Spec\App\Domain;

use App\Domain\HouseholdId;
use App\Domain\Invitation;
use App\Domain\InvitationId;
use App\Domain\Person;
use App\Domain\PersonId;
use PhpSpec\ObjectBehavior;

class HouseholdSpec extends ObjectBehavior
{
    private $id;

    private $householderId;

    public function let()
    {
        $this->id = HouseholdId::generate();
        $this->householderId = PersonId::generate();

        $this->beConstructedThrough(
            'create',
            [
                $this->id,
                'Home',
                $this->householderId,
            ]
        );
    }

    public function it_is_constructed_properly()
    {
        $this->id()->shouldBeLike($this->id);
        $this->name()->shouldReturn('Home');
        $this->householder()->personId()->shouldBeLike($this->householderId);
    }

    public function it_has_householder()
    {
        $this->householder()->personId()->shouldBeLike($this->householderId);
    }

    public function it_has_occupants()
    {
        $this->occupants()->shouldBeArray();
    }

    public function it_allows_to_add_occupant()
    {
        $personId = PersonId::generate();

        $this->addOccupant($personId);

        $this->occupants()->shouldHaveCount(2);
        $this->occupants()->shouldBeLike(
            [
                $this->householder(),
                $this->getOccupant($personId),
            ]
        );
    }

    public function it_allows_to_get_occupant_by_person_id()
    {
        $personId = PersonId::generate();

        $this->addOccupant($personId);

        $this->getOccupant($personId)->personId()->shouldBeLike($personId);
    }

    public function it_doesnt_allow_to_duplicate_occupant()
    {
        $personId = PersonId::generate();

        $this->addOccupant($personId);

        $this->shouldThrow()
            ->during('addOccupant', [$personId]);
    }

    public function it_allows_to_change_householder()
    {
        $personId = PersonId::generate();

        $this->addOccupant($personId);
        $this->changeHouseholder($personId);

        $this->householder()
            ->personId()
            ->equals($personId)
            ->shouldReturn(true);
    }

    public function it_allows_to_remove_occupant()
    {
        $personId = PersonId::generate();

        $this->addOccupant($personId);
        $this->removeOccupant($personId);

        $this->occupants()->shouldHaveCount(1);
        $this->getOccupant($personId)->shouldReturn(null);
    }

    public function it_doesnt_allow_to_remove_householder()
    {
        $householder = $this->householder();

        $this->shouldThrow()->during('removeOccupant', [$householder->personId()]);
    }

    public function it_allows_to_invite_person()
    {
        $person = $this->createPerson();

        $this->invitePerson($person)->shouldBeAnInstanceOf(Invitation::class);
        $this->invitations()->shouldHaveCount(1);
    }

    public function it_doesnt_allow_to_invite_same_person_twice()
    {
        $person = $this->createPerson();

        $this->invitePerson($person);
        $this->shouldThrow()->during('invitePerson', [$person]);
        $this->invitations()->shouldHaveCount(1);
    }

    public function it_allows_to_accept_invitation()
    {
        $person = $this->createPerson();
        $invitation = $this->invitePerson($person);

        $this->acceptInvitation($invitation->id());

        $this->invitations()->shouldHaveCount(0);
        $this->getOccupant($person->id())->personId()->shouldBeLike($person->id());
    }

    public function it_doesnt_allow_to_accept_invitation_by_invalid_id()
    {
        $this->shouldThrow()->during('acceptInvitation', [InvitationId::generate()]);
    }

    public function createPerson(): Person
    {
        $person = Person::register(PersonId::generate(), 'person1', 'person1@example.com');

        return $person;
    }
}
