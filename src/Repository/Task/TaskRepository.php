<?php

namespace App\Repository\Task;

use App\Domain\Entity\Task\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\AbstractUid;


class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /** @return Collection<string, Task> */
    public function getUsersTask(AbstractUid $userId): Collection
    {

        $query = $this->createQueryBuilder('task')
            ->where('task.user = :user')
            ->setParameter('user', $userId, UuidType::NAME)
            ->getQuery();


        return new ArrayCollection($query->getResult());
    }
}