<?php
declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Exception;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use JsonSerializable;
use App\Domain\Shared\Exception\InvalidTimestampException;

class Timestamp implements JsonSerializable
{
    const TIMEZONE = 'UTC';

    const FORMAT = DateTime::ATOM;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $timestamp;

    /**
     * Timestamp constructor.
     * @param DateTimeImmutable $timestamp
     */
    private function __construct(DateTimeImmutable $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return Timestamp
     * @throws InvalidTimestampException
     */
    public static function now(): Timestamp
    {
        return static::fromString('now');
    }

    /**
     * @param string $timestamp
     * @param string $timezone
     * @return Timestamp
     * @throws InvalidTimestampException
     */
    public static function fromString(string $timestamp, $timezone = self::TIMEZONE): Timestamp
    {
        try {
            return new self(
                (new DateTimeImmutable(
                    $timestamp,
                    new DateTimeZone($timezone)
                ))
            );
        } catch (Exception $e) {
            throw new InvalidTimestampException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->timestamp->format(self::FORMAT);
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return (string) $this;
    }
}
