<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

final class Constants
{
    public const PERSON_1_ID = '37a3c809-3048-453a-9cfa-34efbdff35a7';
    public const PERSON_2_ID = '8991456f-e8e3-476a-bbd9-3fdf558ec0bc';
    public const PERSON_3_ID = '45dde32c-c98f-41b4-a513-fda8673240f3';

    public const PET_1_ID = '62263150-99cf-4df3-baf6-164d82d4fe7c';
    public const PET_2_ID = 'de4b120f-a908-47c7-b799-0fa0a165f7b6';
    public const PET_3_ID = '82432256-4a41-42d0-9bd5-72716dfe146c';

    /**
     * @var \ReflectionClass
     */
    private static $reflection;

    public static function getValue(string $constName)
    {
        if (null === self::$reflection) {
            self::$reflection = new \ReflectionClass(__CLASS__);
        }

        return self::$reflection->getConstant($constName);
    }
}
