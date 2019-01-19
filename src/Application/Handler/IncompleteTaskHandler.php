<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\IncompleteTask;
use App\Domain\TaskCompletions;
use App\Domain\Tasks;

final class IncompleteTaskHandler
{
    /**
     * @var Tasks
     */
    private $tasks;
    /**
     * @var TaskCompletions
     */
    private $taskCompletions;

    public function __construct(Tasks $tasks, TaskCompletions $taskCompletions)
    {
        $this->tasks = $tasks;
        $this->taskCompletions = $taskCompletions;
    }

    public function __invoke(IncompleteTask $command): void
    {
        $this->tasks->getById($command->taskId());

        $now = new \DateTimeImmutable();
        $currentCompletion = $this->taskCompletions->findByTaskIdAndDay($command->taskId(), $now);

        if (!$currentCompletion) {
            return;
        }

        $this->taskCompletions->delete($currentCompletion);
    }
}
