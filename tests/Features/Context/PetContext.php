<?php

namespace App\Tests\Features\Context;

use App\Domain\Persons;
use App\Domain\Pet;
use App\Domain\PetId;
use App\Domain\Pets;
use App\Domain\PetType;
use Behat\Behat\Context\Context;

final class PetContext implements Context
{
    /**
     * @var Pets
     */
    private $pets;

    /**
     * @var Persons
     */
    private $persons;

    public function __construct(Pets $pets, Persons $persons)
    {
        $this->pets = $pets;
        $this->persons = $persons;
    }

    /**
     * @Given /^there is a "([^"]*)" "([^"]*)" identified by "([^"]*)" belonging to "([^"]*)"$/
     */
    public function thereIsAPetIdentifiedByBelongingTo(string $type, string $name, string $id, string $username)
    {
        $person = $this->persons->findByUsername($username);

        $pet = Pet::create(
            PetId::fromString($id),
            new PetType($type),
            $person->id(),
            $name
        );

        $this->pets->save($pet);
    }
}
