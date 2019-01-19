<?php

declare(strict_types=1);

namespace App\Domain;

final class TaskCompletion
{
    /**
     * @var TaskCompletionId
     */
    private $id;
    /**
     * @var TaskId
     */
    private $taskId;
    /**
     * @var PersonId
     */
    private $completedBy;
    /**
     * @var \DateTimeImmutable
     */
    private $completedAt;

    public static function create(TaskId $taskId, PersonId $completedBy, \DateTimeImmutable $completedAt): self
    {
        $taskCompletion = new self();
        $taskCompletion->id = TaskCompletionId::generate();
        $taskCompletion->taskId = $taskId;
        $taskCompletion->completedBy = $completedBy;
        $taskCompletion->completedAt = $completedAt;

        return $taskCompletion;
    }

    public function taskId(): TaskId
    {
        return $this->taskId;
    }

    public function completedBy(): PersonId
    {
        return $this->completedBy;
    }

    public function completedAt(): \DateTimeImmutable
    {
        return $this->completedAt;
    }
}
