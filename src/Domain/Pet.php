<?php

declare(strict_types=1);

namespace App\Domain;

use Assert\Assertion;

final class Pet
{
    /**
     * @var PetId
     */
    private $id;

    /**
     * @var PetType
     */
    private $type;

    /**
     * @var PersonId
     */
    private $ownerId;

    /**
     * @var string
     */
    private $name;
    /**
     * @var Gender|null
     */
    private $gender;
    /**
     * @var int|null
     */
    private $birthYear;

    private function __construct()
    {
    }

    public static function create(PetId $id, PetType $type, PersonId $ownerId, string $name): self
    {
        $pet = new self();
        $pet->id = $id;
        $pet->type = $type;
        $pet->ownerId = $ownerId;
        $pet->name = $name;

        return $pet;
    }

    public function id(): PetId
    {
        return $this->id;
    }

    public function type(): PetType
    {
        return $this->type;
    }

    public function ownerId(): PersonId
    {
        return $this->ownerId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function gender(): ? Gender
    {
        return $this->gender;
    }

    public function setBirthYear(?int $year): void
    {
        if (null !== $year) {
            Assertion::integer($year);
            Assertion::between($year, 1950, (int) date('Y'));
        }

        $this->birthYear = $year;
    }

    public function birthYear(): ?int
    {
        return $this->birthYear;
    }
}
