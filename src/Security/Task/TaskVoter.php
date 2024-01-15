<?php

namespace App\Security\Task;

use App\Domain\Entity\Task\Task;
use App\Domain\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($task, $user),
            self::EDIT => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => throw new \LogicException('Unexpected ability')
        };
    }


    private function canView(Task $task, User $user): bool
    {
        return $this->isTaskOwner($task, $user);
    }

    private function canEdit(Task $task, User $user): bool
    {
        return $this->isTaskOwner($task, $user);
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $this->isTaskOwner($task, $user);
    }

    private function isTaskOwner(Task $task, User $user): bool
    {
        return $task->getUser()->getId()->equals($user->getId());
    }
}