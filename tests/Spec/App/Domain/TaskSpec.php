<?php

namespace Spec\App\Domain;

use App\Domain\PetId;
use App\Domain\RecurrenceRule;
use App\Domain\Task;
use App\Domain\TaskId;
use Assert\InvalidArgumentException;
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

    public function it_can_be_renamed()
    {
        $this->rename('New name');
        $this->name()->shouldReturn('New name');
    }

    public function it_doesnt_allow_to_rename_with_empty_name()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('rename', ['']);
    }

    public function it_doesnt_allow_to_set_too_short_name()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('rename', ['Aa']);
    }

    public function it_doesnt_allow_to_set_too_long_name()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('rename', [str_repeat('A', 256)]);
    }

    public function it_allows_to_set_recurrence_rule()
    {
        $rule = RecurrenceRule::create(new \DateTimeImmutable(), 'FREQ=MONTHLY;');

        $this->updateRecurrenceRule($rule);
        $this->recurrenceRule()->shouldReturn($rule);
    }
}
