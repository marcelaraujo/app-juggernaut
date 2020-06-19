<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Task\Doctrine\Orm\Type;

use App\Domain\Shared\ValueObject\Uuid as UuidValueObject;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType;

class Uuid extends UuidType
{
    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof UuidValueObject) {
            return (string) $value;
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', 'DateTime']
        );
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $dt = UuidValueObject::fromString($value);

        return $dt;
    }
}
