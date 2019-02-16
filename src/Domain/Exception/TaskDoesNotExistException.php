<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\TaskId;

final class TaskDoesNotExistException extends Exception
{
    public static function withId(TaskId $taskId): self
    {
        return new self(sprintf('Task with ID "%s" does not exist', (string) $taskId));
    }
}
