<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\TaskId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class RemoveTask extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withId(string $id): self
    {
        return new self(['task_id' => $id]);
    }

    public function taskId(): TaskId
    {
        return TaskId::fromString($this->payload['task_id']);
    }
}
