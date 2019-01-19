<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\InvitePersonToHousehold;
use App\Domain\Households;
use App\Domain\Persons;

final class InvitePersonToHouseholdHandler
{
    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var Households
     */
    private $households;

    /**
     * RegisterPersonHandler constructor.
     */
    public function __construct(Persons $persons, Households $households)
    {
        $this->persons = $persons;
        $this->households = $households;
    }

    public function __invoke(InvitePersonToHousehold $command): void
    {
        $this->validate($command);

        $household = $this->households->findById($command->householdId());
        $person = $this->persons->findById($command->personId());

        $household->invitePerson($person);
    }

    private function validate(InvitePersonToHousehold $command): void
    {
        if (!$this->persons->findById($command->personId())) {
            throw new \DomainException(sprintf('Person with ID %s does not exists', $command->personId()));
        }

        if (!$this->households->findById($command->householdId())) {
            throw new \DomainException(sprintf('Household with ID %s does not exists', $command->householdId()));
        }
    }
}
