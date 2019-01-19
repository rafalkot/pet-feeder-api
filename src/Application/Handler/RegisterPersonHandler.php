<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RegisterPerson;
use App\Domain\Exception\PersonAlreadyExistsException;
use App\Domain\Person;
use App\Domain\Persons;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class RegisterPersonHandler
{
    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * RegisterPersonHandler constructor.
     */
    public function __construct(Persons $persons, UserPasswordEncoderInterface $encoder)
    {
        $this->persons = $persons;
        $this->encoder = $encoder;
    }

    public function __invoke(RegisterPerson $command): void
    {
        $this->validate($command);

        $person = Person::register($command->personId(), $command->username(), $command->email());
        $person->setPassword($this->encoder->encodePassword($person, $command->password()));

        $this->persons->save($person);
    }

    private function validate(RegisterPerson $command): void
    {
        if ($this->persons->findById($command->personId())) {
            throw PersonAlreadyExistsException::withId($command->personId());
        }

        if ($this->persons->findByUsername($command->username())) {
            throw PersonAlreadyExistsException::withUsername($command->username());
        }

        if ($this->persons->findByEmail($command->email())) {
            throw PersonAlreadyExistsException::withEmail($command->email());
        }
    }
}
