<?php
declare(strict_types=1);

namespace App\Domain\Task\Entity;

use App\Domain\Shared\Entity\EntityInterface;
use App\Domain\Shared\Exception\InvalidTimestampException;
use App\Domain\Shared\ValueObject\Timestamp;
use App\Domain\Shared\ValueObject\Uuid;
use JsonSerializable;
use Webmozart\Assert\Assert;

class Task implements JsonSerializable, EntityInterface
{
    /**
     * @var Uuid
     */
    private Uuid $id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var Timestamp
     */
    private Timestamp $dateCreated;

    /**
     * @param Uuid $id
     * @param string $title
     * @param string $description
     * @param Timestamp $dateCreated
     */
    private function __construct(string $title, string $description, Uuid $id, Timestamp $dateCreated)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Timestamp
     */
    public function getDateCreated(): Timestamp
    {
        return $this->dateCreated;
    }

    /**
     * @param string $title
     * @param string $description
     * @param Uuid $id
     * @param Timestamp $dateCreated
     *
     * @throws InvalidTimestampException
     * @return self
     */
    public static function create(
        string $title,
        string $description,
        Uuid $id = null,
        Timestamp $dateCreated = null
    ): self {
        /* title */
        Assert::stringNotEmpty($title, 'The title property was not provided.');
        Assert::maxLength($title, 32, 'The title property expected a value to contain at most %2$s characters. Got: %s');

        /* description */
        Assert::stringNotEmpty($description, 'The description property was not provided.');
        Assert::maxLength($description, 2048, 'The description property expected a value to contain at most %2$s characters. Got: %s');

        $id = $id ?? Uuid::random();
        $title = (string) $title;
        $description = (string) $description;
        $dateCreated = $dateCreated ?? Timestamp::now();

        return new self(
            $title,
            $description,
            $id,
            $dateCreated
        );
    }

    /**
     * @param string $title
     */
    public function updateTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function updateDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date_created' => $this->dateCreated,
        ];
    }
}
