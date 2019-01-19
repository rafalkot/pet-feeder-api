<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\TaskCompletionId;

final class TaskCompletionIdType extends AbstractUuidType
{
    const NAME = 'task_completion_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return TaskCompletionId::class;
    }
}
