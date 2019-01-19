<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\PersonId;
use App\Domain\TaskId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class CompleteTask extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $taskId, string $completedBy): self
    {
        return new self(['task_id' => $taskId, 'completed_by' => $completedBy]);
    }

    public function taskId(): TaskId
    {
        return TaskId::fromString($this->payload['task_id']);
    }

    public function completedBy(): PersonId
    {
        return PersonId::fromString($this->payload['completed_by']);
    }
}
