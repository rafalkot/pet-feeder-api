<?php

declare(strict_types=1);

namespace App\Domain;

use Assert\Assertion;

final class Task
{
    private $id;

    private $petId;

    private $name;

    private $recurrenceRule;

    public static function create(TaskId $id, PetId $petId, string $name, RecurrenceRule $recurrenceRule): self
    {
        $task = new self();
        $task->id = $id;
        $task->petId = $petId;
        $task->setName($name);
        $task->recurrenceRule = $recurrenceRule;

        return $task;
    }

    public function id(): TaskId
    {
        return $this->id;
    }

    public function petId(): PetId
    {
        return $this->petId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->setName($name);
    }

    public function recurrenceRule(): RecurrenceRule
    {
        return $this->recurrenceRule;
    }

    public function updateRecurrenceRule(RecurrenceRule $recurrenceRule): void
    {
        $this->recurrenceRule = $recurrenceRule;
    }

    private function setName(string $name): void
    {
        Assertion::notEmpty($name, 'Task\'s name cannot be empty');
        Assertion::minLength($name, 3);
        Assertion::maxLength($name, 255);

        $this->name = $name;
    }
}
