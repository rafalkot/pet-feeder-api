<?php

namespace Spec\App\Domain;

use App\Domain\Household;
use App\Domain\HouseholdId;
use App\Domain\OccupantId;
use App\Domain\PersonId;
use PhpSpec\ObjectBehavior;

class OccupantSpec extends ObjectBehavior
{
    private $household;

    private $householdId;

    private $personId;

    public function let()
    {
        $this->household = Household::create(HouseholdId::generate(), 'Home', PersonId::generate());
        $this->householdId = $this->household->id();
        $this->personId = PersonId::generate();
    }

    public function it_can_be_constructed()
    {
        $this->beConstructedWith($this->household, OccupantId::generate(), $this->personId);

        $this->householdId()->shouldBeLike($this->householdId);
        $this->personId()->shouldBeLike($this->personId);
    }

    public function it_is_non_owner_by_default()
    {
        $this->beConstructedWith($this->household, OccupantId::generate(), $this->personId);

        $this->isOwner()->shouldReturn(false);
    }

    public function it_can_be_constructed_as_owner()
    {
        $this->beConstructedWith($this->household, OccupantId::generate(), $this->personId, true);

        $this->isOwner()->shouldReturn(true);
    }
}
