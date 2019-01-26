<?php

namespace Spec\App\Domain;

use App\Domain\Person;
use App\Domain\PersonId;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class PersonSpec extends ObjectBehavior
{
    const UUID = '37a3c809-3048-453a-9cfa-34efbdff35a7';

    public function it_is_initializable()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::fromString(self::UUID),
                'person',
                'person@example.com',
            ]
        );
        $this->setPassword('passwordhash');

        $this->shouldHaveType(Person::class);

        $this->id()->shouldBeLike(PersonId::fromString(self::UUID));
        $this->email()->shouldReturn('person@example.com');
        $this->getUsername()->shouldReturn('person');
        $this->getPassword()->shouldReturn('passwordhash');
    }

    public function it_doesnt_allow_to_create_with_empty_username()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::generate(),
                '',
                'person@example.com',
            ]
        );

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_too_short_username()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::generate(),
                'aaaa',
                'person@example.com',
            ]
        );

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedThrough(
            'register',
            [
                PersonId::generate(),
                ' aa ',
                'person@example.com',
            ]
        );

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_too_long_username()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::generate(),
                str_repeat('a', 21),
                'person@example.com',
            ]
        );

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_doesnt_allow_to_register_with_invalid_email()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::generate(),
                'person',
                'invalid_email',
            ]
        );

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_is_serializable()
    {
        $this->beConstructedThrough(
            'register',
            [
                PersonId::fromString(self::UUID),
                'person',
                'person@example.com',
            ]
        );
        $this->setPassword('passwordhash');

        $serialized = $this->serialize();
        $this->unserialize($serialized);

        $this->id()->shouldBeLike(PersonId::fromString(self::UUID));
        $this->getUsername()->shouldBe('person');
        $this->email()->shouldBe('person@example.com');
        $this->getPassword()->shouldBe('passwordhash');
    }
}
