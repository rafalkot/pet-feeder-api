<?php

namespace Spec\App\Domain;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\UUID;
use PhpSpec\ObjectBehavior;

class UUIDSpec extends ObjectBehavior
{
    private const VALID_UUID = '12403754-2272-4137-9d2b-c2525324ef5d';

    public function let()
    {
        $this->beConstructedWith(self::VALID_UUID);
    }

    public function it_is_constructed_properly()
    {
        $this->id()->shouldBe(self::VALID_UUID);
        $this->__toString()->shouldBeLike(self::VALID_UUID);
    }

    public function it_is_comparable()
    {
        $this->equals(new UUID(self::VALID_UUID))->shouldReturn(true);
        $this->equals(new UUID('f90002ec-1f81-4e42-9a65-cd485d8a0879'))->shouldReturn(false);
    }

    public function it_throws_exception_on_invalid_uuid_string()
    {
        $this->beConstructedWith('invalid-uuid');

        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    public function it_allows_to_generate_uuid()
    {
        $this->beConstructedThrough('generate');
        $this->shouldBeAnInstanceOf(UUID::class);
    }

    public function it_allows_to_construct_from_string()
    {
        $this->beConstructedThrough(
            'fromString',
            [
                self::VALID_UUID,
            ]
        );
        $this->shouldBeAnInstanceOf(UUID::class);
        $this->id()->shouldReturn(self::VALID_UUID);
    }
}
