<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\TaskCompletion;
use App\Domain\TaskCompletions;
use App\Domain\TaskId;
use Doctrine\ORM\EntityManagerInterface;

//wspieram 71260

final class ORMTaskCompletions implements TaskCompletions
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PetRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(TaskCompletion $taskCompletion): void
    {
        $this->entityManager->persist($taskCompletion);
        $this->entityManager->flush();
    }

    public function delete(TaskCompletion $taskCompletion): void
    {
        $this->entityManager->remove($taskCompletion);
        $this->entityManager->flush();
    }

    public function findByTaskIdAndDay(TaskId $id, \DateTimeInterface $day): ?TaskCompletion
    {
        return $this->entityManager->createQueryBuilder()
            ->select('tc')
            ->from(TaskCompletion::class, 'tc')
            ->where('tc.taskId = :taskId')
            ->andWhere('DATE(tc.completedAt) = :date')
            ->setParameter('taskId', $id)
            ->setParameter('date', $day->format('Y-m-d'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getByTaskIdAndDay(TaskId $id, \DateTimeInterface $day): TaskCompletion
    {
        $taskCompletion = $this->findByTaskIdAndDay($id, $day);

        if (!$taskCompletion) {
            throw new \Exception('Task completion not found');
        }

        return $taskCompletion;
    }
}
