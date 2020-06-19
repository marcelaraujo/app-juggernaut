<?php
declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use JsonSerializable;
use App\Domain\Shared\String\Uuid\RamseyUuidGenerator;
use Webmozart\Assert\Assert;

class Uuid implements JsonSerializable
{
    /**
     * @var string
     */
    protected string $value;

    /**
     * Uuid constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);

        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function random(): self
    {
        return new static(RamseyUuidGenerator::generate());
    }

    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): self
    {
        return new static($value);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param Uuid $other
     * @return bool
     */
    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value();
    }

    /**
     * @param $id
     */
    private function ensureIsValidUuid($id): void
    {
        Assert::uuid($id);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return (string) $this->value();
    }
}
