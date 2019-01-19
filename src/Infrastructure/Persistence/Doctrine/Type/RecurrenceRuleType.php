<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\RecurrenceRule;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

final class RecurrenceRuleType extends JsonType
{
    const NAME = 'recurrence_rule';

    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param string $value
     *
     * @throws \Exception
     *
     * @return RecurrenceRule|mixed|null
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $data = json_decode($value, true);

        return RecurrenceRule::create(
            \DateTimeImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $data['start_date'],
                new \DateTimeZone($data['time_zone'])
            ),
            $data['rule']
        );
    }

    /**
     * @param RecurrenceRule $value
     *
     * @throws ConversionException
     *
     * @return false|mixed|string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof RecurrenceRule) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return json_encode(
            [
                'start_date' => $value->startDate()->format('Y-m-d H:i:s'),
                'time_zone' => $value->startDate()->getTimezone()->getName(),
                'rule' => $value->ruleString(),
            ]
        );
    }
}
