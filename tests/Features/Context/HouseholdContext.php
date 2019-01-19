<?php

namespace App\Tests\Features\Context;

use App\Domain\Household;
use App\Domain\HouseholdId;
use App\Domain\Households;
use App\Domain\Persons;
use Behat\Behat\Context\Context;

final class HouseholdContext implements Context
{
    /**
     * @var Households
     */
    private $households;

    /**
     * @var Persons
     */
    private $persons;

    /**
     * HouseholdContext constructor.
     */
    public function __construct(Households $households, Persons $persons)
    {
        $this->households = $households;
        $this->persons = $persons;
    }

    /**
     * @Given /^there is a household "([^"]*)" identified by "([^"]*)" belonging to "([^"]*)"$/
     */
    public function thereIsAHouseholdIdentifiedByBelongingTo(string $name, string $id, string $username)
    {
        $person = $this->persons->findByUsername($username);

        $household = Household::create(
            HouseholdId::fromString($id),
            $name,
            $person->id()
        );

        $this->households->save($household);
    }
}
