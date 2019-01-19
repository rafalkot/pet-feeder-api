<?php

declare(strict_types=1);

namespace App\Domain;

use Assert\Assertion;
use Symfony\Component\Security\Core\User\UserInterface;

final class Person implements UserInterface, \Serializable
{
    /**
     * @var PersonId
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    public static function register(PersonId $id, string $username, string $email): self
    {
        $person = new self();
        $person->id = $id;
        $person->setUsername($username);
        $person->setEmail($email);

        return $person;
    }

    public function id(): PersonId
    {
        return $this->id;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    private function setUsername(string $username): void
    {
        Assertion::notBlank($username);
        $username = trim($username);
        Assertion::minLength($username, 5);
        Assertion::maxLength($username, 20);

        $this->username = $username;
    }

    private function setEmail(string $email): void
    {
        Assertion::email($email);

        $this->email = $email;
    }
}
