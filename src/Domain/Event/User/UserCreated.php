<?php

namespace App\Domain\Event\User;

use App\Domain\Entity\User\User;
use App\Domain\Event\Event;

/**
 * Class UserCreated
 * @package App\Domain\Event\User
 */
class UserCreated extends Event
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}