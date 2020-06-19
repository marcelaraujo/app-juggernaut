<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Task\Doctrine\Orm\Type;

use App\Domain\Shared\ValueObject\Timestamp as TimestampValueObject;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Timestamp extends Type
{
    /**
     * Type name
     *
     * @var string
     */
    const TIMESTAMP = 'timestamp';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::TIMESTAMP;
    }

    /**
     * @inheritDoc
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof TimestampValueObject) {
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

        $dt = TimestampValueObject::fromString($value);

        return $dt;
    }
}
