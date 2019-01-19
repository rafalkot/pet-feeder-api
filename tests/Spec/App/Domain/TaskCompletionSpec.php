<?php

namespace Spec\App\Domain;

use App\Domain\PersonId;
use App\Domain\TaskCompletion;
use App\Domain\TaskId;
use PhpSpec\ObjectBehavior;

class TaskCompletionSpec extends ObjectBehavior
{
    private $taskId;
    private $completedBy;
    private $completedAt;

    public function let()
    {
        $this->taskId = TaskId::generate();
        $this->completedBy = PersonId::generate();
        $this->completedAt = new \DateTimeImmutable('2010-01-01 00:00:00');

        $this->beConstructedThrough(
            'create',
            [
                $this->taskId,
                $this->completedBy,
                $this->completedAt,
            ]
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TaskCompletion::class);
        $this->taskId()->shouldReturn($this->taskId);
        $this->completedBy()->shouldReturn($this->completedBy);
        $this->completedAt()->shouldReturn($this->completedAt);
    }
}
