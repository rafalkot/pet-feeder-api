<?php

namespace App\Tests\Features\Context;

use App\Domain\PetId;
use App\Domain\RecurrenceRule;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\Tasks;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

final class TaskContext implements Context
{
    /**
     * @var Tasks
     */
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @Given /^there is a task with following details:$/
     */
    public function thereIsATaskWithFollowingDetails(TableNode $tasks)
    {
        foreach ($tasks->getColumnsHash() as $key => $val) {
            $task = Task::create(
                TaskId::fromString($val['task_id']),
                PetId::fromString($val['pet_id']),
                $val['task_name'],
                RecurrenceRule::create(
                    new \DateTimeImmutable(
                        date('Y-m-d').' '.$val['task_hour']
                    ),
                    $val['task_recurrence']
                )
            );

            $this->tasks->save($task);
        }
    }
}
