<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Processor;

use App\Domain\Person;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class PersonProcessor implements ProcessorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * PersonProcessor constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function preProcess(string $id, $object): void
    {
        if (!$object instanceof Person) {
            return;
        }

        $password = $this->encoder->encodePassword($object, $object->getPassword());
        $object->setPassword($password);
    }

    public function postProcess(string $id, $object): void
    {
    }
}
