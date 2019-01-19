<?php

namespace Spec\App\Domain;

use App\Domain\PetId;
use App\Domain\RecurrenceRule;
use App\Domain\Task;
use App\Domain\TaskId;
use PhpSpec\ObjectBehavior;

class TaskSpec extends ObjectBehavior
{
    private $taskId;
    private $petId;
    private $name;
    private $recurrenceRule;

    public function let()
    {
        $this->taskId = TaskId::generate();
        $this->petId = PetId::generate();
        $this->name = 'Walk';
        $this->recurrenceRule = RecurrenceRule::create(new \DateTimeImmutable(), 'FREQ=DAILY');

        $this->beConstructedThrough('create', [
             $this->taskId,
             $this->petId,
             $this->name,
            $this->recurrenceRule,
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Task::class);
        $this->id()->shouldReturn($this->taskId);
        $this->petId()->shouldReturn($this->petId);
        $this->name()->shouldReturn($this->name);
        $this->recurrenceRule()->shouldReturn($this->recurrenceRule);
    }
}
