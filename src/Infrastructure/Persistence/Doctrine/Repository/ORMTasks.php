<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Exception\TaskDoesNotExistException;
use App\Domain\Task;
use App\Domain\TaskId;
use App\Domain\Tasks;
use Doctrine\ORM\EntityManagerInterface;

final class ORMTasks implements Tasks
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function delete(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function findById(TaskId $id): ? Task
    {
        return $this->entityManager->find(Task::class, $id);
    }

    public function getById(TaskId $id): Task
    {
        $task = $this->findById($id);

        if (!$task) {
            throw TaskDoesNotExistException::withId($id);
        }

        return $task;
    }
}
