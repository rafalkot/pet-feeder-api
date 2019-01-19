<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RegisterPet;
use App\Domain\Exception\PersonDoesNotExistException;
use App\Domain\Exception\PetAlreadyExistsException;
use App\Domain\Persons;
use App\Domain\Pet;
use App\Domain\Pets;

final class RegisterPetHandler
{
    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var Pets
     */
    private $pets;

    /**
     * RegisterPersonHandler constructor.
     */
    public function __construct(Persons $persons, Pets $pets)
    {
        $this->persons = $persons;
        $this->pets = $pets;
    }

    public function __invoke(RegisterPet $command): void
    {
        $this->validate($command);

        $pet = Pet::create(
            $command->petId(),
            $command->type(),
            $command->ownerId(),
            $command->name()
        );

        $this->pets->save($pet);
    }

    private function validate(RegisterPet $command): void
    {
        if (!$this->persons->findById($command->ownerId())) {
            throw PersonDoesNotExistException::withId($command->ownerId());
        }

        if ($this->pets->findById($command->petId())) {
            throw PetAlreadyExistsException::withId($command->petId());
        }

        $ownersPets = $this->pets->findAllByOwnerId($command->ownerId());

        foreach ($ownersPets as $pet) {
            if ($pet->name() === $command->name()) {
                throw PetAlreadyExistsException::withName($command->name());
            }
        }
    }
}
