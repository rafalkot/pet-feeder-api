<?php

declare(strict_types=1);

namespace App\Domain;

interface TaskCompletions
{
    public function save(TaskCompletion $taskCompletion): void;

    public function delete(TaskCompletion $taskCompletion): void;

    public function findByTaskIdAndDay(TaskId $taskId, \DateTimeInterface $day): ?TaskCompletion;

    public function getByTaskIdAndDay(TaskId $taskId, \DateTimeInterface $day): TaskCompletion;
}
