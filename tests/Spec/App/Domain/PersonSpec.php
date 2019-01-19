<?php

namespace Spec\App\Domain;

use App\Domain\Person;
use App\Domain\PersonId;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class PersonSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedThrough('register', [
            PersonId::fromString('37a3c809-3048-453a-9cfa-34efbdff35a7'),
            'person',
            'person@example.com'
        ]);

        $this->shouldHaveType(Person::class);

        $this->id()->shouldBeLike(PersonId::fromString('37a3c809-3048-453a-9cfa-34efbdff35a7'));
        $this->email()->shouldReturn('person@example.com');
        $this->getUsername()->shouldReturn('person');
    }

    public function it_doesnt_allow_to_create_with_empty_username()
    {
        $this->beConstructedThrough('register', [
            PersonId::generate(),
            '',
            'person@example.com'
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_too_short_username()
    {
        $this->beConstructedThrough('register', [
            PersonId::generate(),
            'aaaa',
            'person@example.com'
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedThrough('register', [
            PersonId::generate(),
            ' aa ',
            'person@example.com'
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_too_long_username()
    {
        $this->beConstructedThrough('register', [
            PersonId::generate(),
            str_repeat('a', 21),
            'person@example.com'
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_invalid_email()
    {
        $this->beConstructedThrough('register', [
            PersonId::generate(),
            'person',
            'invalid_email'
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
