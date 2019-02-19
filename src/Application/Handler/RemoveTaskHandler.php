<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RemoveTask;
use App\Domain\Tasks;

final class RemoveTaskHandler
{
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }

    public function __invoke(RemoveTask $command): void
    {
        $task = $this->tasks->getById($command->taskId());

        $this->tasks->delete($task);
    }
}
