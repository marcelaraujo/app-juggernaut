<?php
declare(strict_types=1);

namespace App\Domain\Shared\Exception;

use Exception;

class InvalidTimestampException extends Exception
{
    protected $message = 'Datetime Malformed or not valid';
}
