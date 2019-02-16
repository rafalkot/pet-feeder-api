<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Application\Command\UpdateTask;
use App\Domain\PersonId;
use App\Domain\PetId;
use App\Domain\TaskId;
use App\Domain\Exception\TaskDoesNotExistException;

final class TaskTest extends ApplicationTestCase
{
    /**
     * @var PersonId
     */
    private $ownerId;
    /**
     * @var PetId
     */
    private $petId;
    /**
     * @var TaskId
     */
    private $taskId;

    protected function setUp()
    {
        parent::setUp();

        $this->clearDatabase();

        $this->ownerId = $this->context->registerPerson(
            'person1',
            'person1@example.com',
            'password'
        );
        $this->petId = $this->context->registerPet(
            $this->ownerId->id(),
            'cat',
            'Rocky'
        );
        $this->taskId = $this->context->scheduleTask(
            $this->petId->id(),
            'Walk'
        );
    }

    public function test_update_task()
    {
        $command = new UpdateTask(
            [
                'task_id' => $this->taskId->id(),
                'name' => 'New name',
                'hour' => '12:00:00',
                'time_zone' => 'Europe/Berlin',
                'recurrence' => 'FREQ=DAILY;BYDAY=MO,TU',
            ]
        );

        $this->context->dispatchCommand($command);

        $task = $this->context->tasks()->getById($command->taskId());
        $this->assertEquals($command->taskId(), $task->id());
        $this->assertEquals($command->name(), $task->name());
        $this->assertEquals($command->hour(), $task->recurrenceRule()->startDate()->format('H:i:s'));
        $this->assertEquals($command->timeZone(), $task->recurrenceRule()->timeZone());
        $this->assertEquals($command->recurrenceRule(), $task->recurrenceRule());
    }

    public function test_update_task_invalid_id()
    {
        $this->expectException(TaskDoesNotExistException::class);

        $command = new UpdateTask(
            [
                'task_id' => TaskId::generate()->id(),
                'name' => 'New name',
                'hour' => '12:00:00',
                'time_zone' => 'Europe/Berlin',
                'recurrence' => 'FREQ=DAILY;BYDAY=MO,TU',
            ]
        );

        $this->context->dispatchCommand($command);
    }
}
