<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\TaskId;

final class TaskIdType extends AbstractUuidType
{
    const NAME = 'task_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return TaskId::class;
    }
}
