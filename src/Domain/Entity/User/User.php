<?php

namespace App\Domain\Entity\User;

use App\Domain\Entity\Task\Task;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Domain\ValueObject\User\Email;
use App\Repository\User\UserRepository;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private AbstractUid $id;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    private Email $email;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class, cascade: ['persist'])]
    private Collection $tasks;

    public function __construct(
        AbstractUid $id,
        Email $email
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->tasks = new ArrayCollection();
    }

    public static function register(AbstractUid $id, Email $email): User
    {
        return new User($id, $email);
    }

    public function createTask(
        AbstractUid $id,
        Title $title,
        Description $description,
        CarbonInterface $createdAt,
        ?CarbonInterface $completedAt = null
    ): Task {
        $task = Task::createTask($id, $title, $description, $this, $createdAt, $completedAt);
        $this->tasks->add($task);

        return $task;
    }

    //GETTERS
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getId(): AbstractUid
    {
        return $this->id;
    }

}