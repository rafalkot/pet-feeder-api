<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Household;
use App\Domain\HouseholdId;
use App\Domain\Households;
use Doctrine\ORM\EntityManagerInterface;

final class ORMHouseholds implements Households
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * HouseholdRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Household $household): void
    {
        $this->entityManager->persist($household);
        $this->entityManager->flush();
    }

    public function delete(Household $household): void
    {
        $this->entityManager->remove($household);
        $this->entityManager->flush();
    }

    public function findById(HouseholdId $id): ? Household
    {
        return $this->entityManager->find(Household::class, $id);
    }
}
