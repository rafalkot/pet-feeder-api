<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Application\Command\RegisterPerson;
use App\Domain\Exception\PersonAlreadyExistsException;
use App\Domain\PersonId;

final class PersonTest extends ApplicationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->clearDatabase();
    }

    public function test_register_person()
    {
        $command = RegisterPerson::withData(
            PersonId::generate()->id(),
            'person1',
            'person1@example.com',
            'password'
        );

        $this->context->dispatchCommand($command);

        $person = $this->context->persons()->findById($command->personId());

        $this->assertSame('person1', $person->getUsername());
        $this->assertSame('person1@example.com', $person->email());
    }

    public function test_register_person_with_existing_username()
    {
        $this->expectException(PersonAlreadyExistsException::class);

        $command = RegisterPerson::withData(
            PersonId::generate()->id(),
            'person1',
            'person2@example.com',
            'password'
        );

        $this->context->dispatchCommand($command);
        $this->context->dispatchCommand($command);
    }

    public function test_register_person_with_existing_email()
    {
        $this->expectException(PersonAlreadyExistsException::class);

        $command = RegisterPerson::withData(
            PersonId::generate()->id(),
            'person2',
            'person1@example.com',
            'password'
        );

        $this->context->dispatchCommand($command);
        $this->context->dispatchCommand($command);
    }
}
