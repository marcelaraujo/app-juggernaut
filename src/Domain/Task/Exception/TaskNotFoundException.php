<?php
declare(strict_types=1);

namespace App\Domain\Task\Exception;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TaskNotFoundException extends DomainRecordNotFoundException
{
    protected $message = 'The task you requested does not exist.';
}
