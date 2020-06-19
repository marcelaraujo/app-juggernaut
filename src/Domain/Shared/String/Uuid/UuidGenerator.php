<?php
declare(strict_types=1);

namespace App\Domain\Shared\String\Uuid;

interface UuidGenerator
{
    /**
     * @return string
     */
    public static function generate(): string;
}
