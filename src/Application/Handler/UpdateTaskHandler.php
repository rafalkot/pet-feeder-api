<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UpdateTask;
use App\Domain\Tasks;

final class UpdateTaskHandler
{
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }

    public function __invoke(UpdateTask $command): void
    {
        $task = $this->tasks->getById($command->taskId());

        $task->rename($command->name());
        $task->updateRecurrenceRule($command->recurrenceRule());

        $this->tasks->save($task);
    }
}
