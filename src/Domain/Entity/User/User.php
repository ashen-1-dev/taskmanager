<?php

namespace App\Domain\Entity\User;

use App\Domain\Entity\Task\Task;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Domain\ValueObject\User\Email;
use App\Domain\ValueObject\User\Password;
use App\Repository\User\UserRepository;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\AbstractUid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private AbstractUid $id;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    private Email $email;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class, cascade: ['persist'])]
    private Collection $tasks;

    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    private Password $password;

    public function __construct(
        AbstractUid $id,
        Email $email,
        Password $password
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->tasks = new ArrayCollection();
    }

    public static function register(AbstractUid $id, Email $email, Password $password): User
    {
        return new User($id, $email, $password);
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

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password->toString();
    }

    public function setPassword(Password $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {}

    public function getUserIdentifier(): string
    {
        return $this->email->toString();
    }
}