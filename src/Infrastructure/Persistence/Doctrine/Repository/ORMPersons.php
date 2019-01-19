<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Person;
use App\Domain\PersonId;
use App\Domain\Persons;
use Doctrine\ORM\EntityManagerInterface;

final class ORMPersons implements Persons
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

    public function save(Person $person): void
    {
        $this->entityManager->persist($person);
        $this->entityManager->flush();
    }

    public function delete(Person $person): void
    {
        $this->entityManager->remove($person);
        $this->entityManager->flush();
    }

    public function findById(PersonId $id): ? Person
    {
        return $this->entityManager->find(Person::class, $id);
    }

    public function findByUsername(string $username): ? Person
    {
        return $this->entityManager
            ->getRepository(Person::class)
            ->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ? Person
    {
        return $this->entityManager
            ->getRepository(Person::class)
            ->findOneBy(['email' => $email]);
    }
}
