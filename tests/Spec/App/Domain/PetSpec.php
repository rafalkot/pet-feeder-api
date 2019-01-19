<?php

namespace Spec\App\Domain;

use App\Domain\Gender;
use App\Domain\PersonId;
use App\Domain\PetId;
use App\Domain\PetType;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class PetSpec extends ObjectBehavior
{
    private $id;

    private $ownerId;

    private $type;

    public function let()
    {
        $this->id = PetId::generate();
        $this->ownerId = PersonId::generate();
        $this->type = new PetType('cat');

        $this->beConstructedThrough('create', [
            $this->id,
            $this->type,
            $this->ownerId,
            'Name',
        ]);
    }

    public function it_is_constructed_properly()
    {
        $this->id()->shouldBeLike($this->id);
        $this->type()->shouldBeLike($this->type);
        $this->ownerId()->shouldBeLike($this->ownerId);
        $this->name()->shouldReturn('Name');
    }

    public function it_allows_to_set_gender()
    {
        $this->setGender(Gender::FEMALE());
        $this->gender()->shouldBeLike(Gender::FEMALE());
    }

    public function it_allows_to_set_null_gender()
    {
        $this->setGender(null);
        $this->gender()->shouldReturn(null);
    }

    public function it_allows_to_set_year_of_birth()
    {
        $this->setBirthYear((int) date('Y'));
        $this->birthYear()->shouldReturn((int) date('Y'));
    }

    public function it_allows_to_set_null_year_of_birth()
    {
        $this->setBirthYear(null);
        $this->birthYear()->shouldReturn(null);
    }

    public function it_doesnt_allow_to_set_birth_year_from_future()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setBirthYear', [date('Y') + 1]);
    }

    public function it_doesnt_allow_to_set_birth_year_before_1950()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setBirthYear', [1949]);

        $this->setBirthYear(1950);
    }
}
