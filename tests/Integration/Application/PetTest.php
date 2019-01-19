<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application;

use App\Application\Command\RegisterPet;
use App\Application\Command\RemovePet;
use App\Application\Command\UpdatePetProfile;
use App\Domain\Exception\PersonDoesNotExistException;
use App\Domain\Exception\PetAlreadyExistsException;
use App\Domain\Exception\PetDoesNotExistException;
use App\Domain\PersonId;
use App\Domain\PetId;

final class PetTest extends ApplicationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->clearDatabase();
    }

    public function test_register_pet()
    {
        $ownerId = $this->context->registerPerson('person1', 'person1@example.com', 'password');

        $command = RegisterPet::withData(
            PetId::generate()->id(),
            $ownerId->id(),
            'dog',
            'Rocky'
        );

        $this->context->dispatchCommand($command);

        $pet = $this->context->pets()->getById($command->petId());
        $this->assertEquals($command->petId(), $pet->id());
        $this->assertEquals($command->ownerId(), $pet->ownerId());
        $this->assertEquals($command->type(), $pet->type()->getValue());
        $this->assertEquals($command->name(), $pet->name());
    }

    public function test_register_pet_invalid_owner_id()
    {
        $this->expectException(PersonDoesNotExistException::class);

        $command = RegisterPet::withData(
            PetId::generate()->id(),
            PersonId::generate()->id(),
            'dog',
            'Rocky'
        );

        $this->context->dispatchCommand($command);
    }

    public function test_register_pets_with_same_name()
    {
        $this->expectException(PetAlreadyExistsException::class);

        $ownerId = $this->context->registerPerson('person1', 'person1@example.com', 'password');
        $this->context->registerPet($ownerId->id(), 'dog', 'Rocky');

        $command = RegisterPet::withData(
            PetId::generate()->id(),
            $ownerId->id(),
            'dog',
            'Rocky'
        );

        $this->context->dispatchCommand($command);
    }

    public function test_update_pet_profile()
    {
        $ownerId = $this->context->registerPerson('person1', 'person1@example.com', 'password');
        $petId = $this->context->registerPet($ownerId->id(), 'dog', 'Rocky');

        $command = UpdatePetProfile::withData($petId->id(), 'm', 1991);

        $this->context->dispatchCommand($command);

        $pet = $this->context->pets()->getById($command->petId());

        $this->assertEquals($command->gender(), $pet->gender());
        $this->assertEquals($command->birthYear(), $pet->birthYear());
    }

    public function test_update_pet_profile_invalid_id()
    {
        $this->expectException(PetDoesNotExistException::class);

        $command = UpdatePetProfile::withData(PetId::generate()->id(), 'm', 1991);

        $this->context->dispatchCommand($command);
    }

    public function test_remove_pet_profile()
    {
        $ownerId = $this->context->registerPerson('person1', 'person1@example.com', 'password');
        $petId = $this->context->registerPet($ownerId->id(), 'dog', 'Rocky');

        $command = RemovePet::withId($petId->id());

        $this->context->dispatchCommand($command);

        $this->assertNull($this->context->pets()->findById($petId));
    }

    public function test_remove_pet_profile_invalid_id()
    {
        $this->expectException(PetDoesNotExistException::class);

        $command = RemovePet::withId(PetId::generate()->id());

        $this->context->dispatchCommand($command);
    }
}
