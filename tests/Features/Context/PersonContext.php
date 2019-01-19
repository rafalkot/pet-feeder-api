<?php

declare(strict_types=1);

namespace App\Tests\Features\Context;

use App\Domain\Person;
use App\Domain\PersonId;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class PersonContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * PersonContext constructor.
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    /**
     * @Given there is a person :username with email :email and password :password
     */
    public function thereIsAPersonWithEmailAndPassword($username, $email, $password)
    {
        $person = Person::register(PersonId::generate(), $username, $email);
        $person->setPassword($this->encoder->encodePassword($person, $password));

        $this->manager->persist($person);
        $this->manager->flush();
    }

    /**
     * @Given there is a person :username with email :email and password :password identified by :id
     */
    public function thereIsAPersonWithEmailAndPasswordIdenitiedBy($username, $email, $password, $id)
    {
        $person = Person::register(PersonId::fromString($id), $username, $email);
        $person->setPassword($this->encoder->encodePassword($person, $password));

        $this->manager->persist($person);
        $this->manager->flush();
    }
}
