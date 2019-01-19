<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\UUID;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class AbstractUuidType extends GuidType
{
    /**
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param string $value
     *
     * @return UUID
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $class = $this->getValueObjectClassName();

        return new $class($value);
    }

    /**
     * @param UUID $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    /**
     * @return string
     */
    abstract protected function getValueObjectClassName();
}
