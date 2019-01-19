<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateHousehold;
use App\Domain\Household;
use App\Domain\Households;
use App\Domain\Persons;

final class CreateHouseholdHandler
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

    public function __invoke(CreateHousehold $command): void
    {
        $this->validate($command);

        $household = Household::create(
            $command->householdId(),
            $command->name(),
            $command->ownerId()
        );

        $this->households->save($household);
    }

    private function validate(CreateHousehold $command): void
    {
        if (!$this->persons->findById($command->ownerId())) {
            throw new \DomainException(sprintf('Person with ID %s does not exists', $command->ownerId()));
        }
    }
}
