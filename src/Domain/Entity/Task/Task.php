<?php

namespace App\Domain\Entity\Task;

use App\Domain\Entity\Task\Enum\TaskStatus;
use App\Domain\Entity\User\User;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Exception\Task\CompleteDateBiggerThanCreateDate;
use App\Exception\Task\WrongTaskCompleteDate;
use App\Repository\Task\TaskRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private AbstractUid $id;
    #[ORM\Embedded(class: Title::class, columnPrefix: false)]
    private Title $title;

    #[ORM\Embedded(class: Description::class, columnPrefix: false)]
    private Description $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?CarbonInterface $completedAt;

    private function __construct(
        AbstractUid $id,
        Title $title,
        Description $description,
        User $user,
        CarbonInterface $createdAt,
        ?CarbonInterface $completedAt = null
    ) {
        if (!is_null($completedAt) && $completedAt > $createdAt) {
            throw new CompleteDateBiggerThanCreateDate();
        }

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
    }

    public static function createTask(
        AbstractUid $id,
        Title $title,
        Description $description,
        User $user,
        CarbonInterface $createdAt,
        ?CarbonInterface $completedAt = null
    ): Task {
        return new Task(
            id: $id,
            title: $title,
            description: $description,
            user: $user,
            createdAt: $createdAt,
            completedAt: $completedAt
        );
    }

    public function markAsCompleted(CarbonInterface $completedAt): true
    {
        if ($completedAt->isFuture()) {
            throw new WrongTaskCompleteDate();
        }

        $this->completedAt = $completedAt;

        return true;
    }

    public function editTask(
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt
    ): Task {
        if (!is_null($completedAt) && $completedAt > $this->createdAt) {
            throw new CompleteDateBiggerThanCreateDate();
        }

        $this->title = $title;
        $this->description = $description;
        $this->completedAt = $completedAt;

        return $this;
    }

    // GETTERS
    public function getId(): AbstractUid
    {
        return $this->id;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getCompletedAt(): ?CarbonInterface
    {
        return $this->completedAt;
    }

    public function getStatus(): TaskStatus
    {
        return !is_null($this->completedAt) ? TaskStatus::Completed : TaskStatus::NotCompleted;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('title', new Assert\Valid());
        $metadata->addPropertyConstraint('description', new Assert\Valid());
    }
}