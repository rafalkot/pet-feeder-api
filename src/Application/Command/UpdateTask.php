<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\RecurrenceRule;
use App\Domain\TaskId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class UpdateTask extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public function taskId(): TaskId
    {
        return TaskId::fromString($this->payload['task_id']);
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

    public function recurrenceRule(): RecurrenceRule
    {
        $startDate = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            date('Y-m-d').' '.$this->hour(),
            $this->timeZone()
        );

        return RecurrenceRule::create($startDate, $this->payload['recurrence']);
    }
}
