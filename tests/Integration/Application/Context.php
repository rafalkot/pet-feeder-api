<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Application\Command\RegisterPerson;
use App\Application\Command\RegisterPet;
use App\Application\Command\ScheduleTask;
use App\Domain\PersonId;
use App\Domain\Persons;
use App\Domain\PetId;
use App\Domain\Pets;
use App\Domain\TaskId;
use App\Domain\Tasks;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;

final class Context
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
     * @var Tasks
     */
    private $tasks;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(Persons $persons, Pets $pets, Tasks $tasks, CommandBus $commandBus)
    {
        $this->persons = $persons;
        $this->pets = $pets;
        $this->tasks = $tasks;
        $this->commandBus = $commandBus;
    }

    public function dispatchCommand($command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (CommandDispatchException $ex) {
            throw $ex->getPrevious();
        }
    }

    public function persons(): Persons
    {
        return $this->persons;
    }

    public function pets(): Pets
    {
        return $this->pets;
    }

    public function tasks(): Tasks
    {
        return $this->tasks;
    }

    public function registerPerson(string $username, string $email, string $password): PersonId
    {
        $command = RegisterPerson::withData(PersonId::generate()->id(), $username, $email, $password);

        $this->dispatchCommand($command);

        return $command->personId();
    }

    public function registerPet(string $ownerId, string $type, string $name): PetId
    {
        $command = RegisterPet::withData(PetId::generate()->id(), $ownerId, $type, $name);

        $this->dispatchCommand($command);

        return $command->petId();
    }

    public function scheduleTask(string $petId, string $name, string $recurrence = 'FREQ=DAILY')
    {
        $command = new ScheduleTask([
            'task_id' => TaskId::generate()->id(),
            'pet_id' => $petId,
            'name' => $name,
            'time_zone' => 'Europe/Warsaw',
            'hour' => '08:00:00',
            'recurrence' => $recurrence,
        ]);

        $this->dispatchCommand($command);

        return $command->taskId();
    }
}
