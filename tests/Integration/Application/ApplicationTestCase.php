<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Infrastructure\Persistence\Doctrine\Repository\ORMPersons;
use App\Infrastructure\Persistence\Doctrine\Repository\ORMPets;
use App\Infrastructure\Persistence\Doctrine\Repository\ORMTasks;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Prooph\ServiceBus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class ApplicationTestCase extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var CommandBus
     */
    protected $commandBus;
    /**
     * @var Context
     */
    protected $context;

    protected function setUp()
    {
        parent::bootKernel();

        $this->entityManager = parent::$container->get('doctrine.orm.entity_manager');
        $this->context = new Context(
            new ORMPersons($this->entityManager),
            new ORMPets($this->entityManager),
            new ORMTasks($this->entityManager),
            parent::$container->get('prooph_service_bus.my_command_bus')
        );
    }

    protected function clearDatabase(): void
    {
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
        $this->entityManager->clear();
    }
}
