<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Exception\PetDoesNotExistException;
use App\Domain\PersonId;
use App\Domain\Pet;
use App\Domain\PetId;
use App\Domain\Pets;
use Doctrine\ORM\EntityManagerInterface;

final class ORMPets implements Pets
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

    public function save(Pet $pet): void
    {
        $this->entityManager->persist($pet);
        $this->entityManager->flush();
    }

    public function delete(Pet $pet): void
    {
        $this->entityManager->remove($pet);
        $this->entityManager->flush();
    }

    public function findById(PetId $id): ? Pet
    {
        return $this->entityManager->find(Pet::class, $id);
    }

    public function getById(PetId $id): Pet
    {
        $pet = $this->findById($id);

        if (!$pet) {
            throw PetDoesNotExistException::withId($id);
        }

        return $pet;
    }

    public function findAllByOwnerId(PersonId $id): array
    {
        return $this->entityManager->getRepository(Pet::class)->findBy([
            'ownerId' => $id,
        ]);
    }
}
