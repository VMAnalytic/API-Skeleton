<?php

namespace App\Repository\User;

use App\Domain\Entity\User\User;
use App\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ObjectRepository;

class UserRepository extends AbstractRepository
{
    /**
     * @inheritdoc
     */
    public function getRepository(): ObjectRepository
    {
        return $this->em->getRepository(User::class);
    }

    /**
     * @param User $user
     */
    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    /**
     * @param User $user
     */
    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
