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

    public function test_register_person_with_existing_id()
    {
        $id = PersonId::generate()->id();

        $this->expectException(PersonAlreadyExistsException::class);
        $this->expectExceptionMessage(sprintf('Person with ID "%s" already exists', $id));

        $command = RegisterPerson::withData(
            $id,
            'person1',
            'person2@example.com',
            'password'
        );

        $this->context->dispatchCommand($command);

        $command = RegisterPerson::withData(
            $id,
            'person2',
            'person2@example.com',
            'password'
        );
        $this->context->dispatchCommand($command);
    }

    public function test_register_person_with_existing_username()
    {
        $username = 'person1';

        $this->expectException(PersonAlreadyExistsException::class);
        $this->expectExceptionMessage(sprintf('Person with username "%s" already exists', $username));

        $this->context->registerPerson($username, 'person1@example.com', 'password');

        $command = RegisterPerson::withData(
            PersonId::generate()->id(),
            $username,
            'person2@example.com',
            'password'
        );
        $this->context->dispatchCommand($command);
    }

    public function test_register_person_with_existing_email()
    {
        $email = 'person1@example.com';

        $this->expectException(PersonAlreadyExistsException::class);
        $this->expectExceptionMessage(sprintf('Person with email "%s" already exists', $email));

        $this->context->registerPerson('person1', $email, 'password');

        $command = RegisterPerson::withData(
            PersonId::generate()->id(),
            'person2',
            $email,
            'password'
        );

        $this->context->dispatchCommand($command);
    }
}
