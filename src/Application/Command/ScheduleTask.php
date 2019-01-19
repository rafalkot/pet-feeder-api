<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\PetId;
use App\Domain\TaskId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class ScheduleTask extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public function taskId(): TaskId
    {
        return TaskId::fromString($this->payload['task_id']);
    }

    public function petId(): PetId
    {
        return PetId::fromString($this->payload['pet_id']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }

    public function hour(): string
    {
        return $this->payload['hour'];
    }

    public function timeZone(): \DateTimeZone
    {
        return new \DateTimeZone($this->payload['time_zone']);
    }

    public function recurrenceRule(): string
    {
        return $this->payload['recurrence'];
    }
}
