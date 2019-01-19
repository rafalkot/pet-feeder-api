<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CompleteTask;
use App\Domain\TaskCompletion;
use App\Domain\TaskCompletions;
use App\Domain\Tasks;

final class CompleteTaskHandler
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

    public function __invoke(CompleteTask $command): void
    {
        $task = $this->tasks->getById($command->taskId());
        $now = new \DateTimeImmutable();
        $currentCompletion = $this->taskCompletions->findByTaskIdAndDay($command->taskId(), $now);

        if ($currentCompletion) {
            return;
        }

        $completion = TaskCompletion::create(
            $task->id(),
            $command->completedBy(),
            $now
        );

        $this->taskCompletions->save($completion);
    }
}
