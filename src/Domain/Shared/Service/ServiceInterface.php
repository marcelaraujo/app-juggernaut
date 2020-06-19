<?php
declare(strict_types=1);

namespace App\Domain\Shared\Service;

use App\Domain\Shared\Entity\EntityInterface;

interface ServiceInterface
{
    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function create(EntityInterface $entity): EntityInterface;

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function update(EntityInterface $entity): EntityInterface;
}