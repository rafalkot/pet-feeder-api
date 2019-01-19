<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\ScheduleTask;
use App\Domain\RecurrenceRule;
use App\Domain\Task;
use App\Domain\Tasks;

final class ScheduleTaskHandler
{
    /**
     * @var Tasks
     */
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }

    public function __invoke(ScheduleTask $command): void
    {
        $startDate = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d').' '.$command->hour(),
            $command->timeZone()
        );
        $rule = RecurrenceRule::create($startDate, $command->recurrenceRule());

        $task = Task::create($command->taskId(), $command->petId(), $command->name(), $rule);

        $this->tasks->save($task);
    }
}
