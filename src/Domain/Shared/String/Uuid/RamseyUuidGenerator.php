<?php
declare(strict_types=1);

namespace App\Domain\Shared\String\Uuid;

use Ramsey\Uuid\Uuid;

final class RamseyUuidGenerator implements UuidGenerator
{
    /**
     * Returns the string standard representation of the UUID
     *
     * @return string
     */
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
