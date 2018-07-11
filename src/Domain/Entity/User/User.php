<?php

namespace App\Domain\Entity\User;

use App\Domain\Entity\DomainEventsProviderTrait;
use App\Domain\Event\User\UserCreated;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    use DomainEventsProviderTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * User constructor.
     * @param UserBuilder $userBuilder
     */
    public function __construct(UserBuilder $userBuilder)
    {
        $this->email = $userBuilder->getEmail();
        $this->password = $userBuilder->getEncodedPassword();
        $this->registeredAt = new \DateTime();

        $this->rememberThat(new UserCreated($this));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt(): \DateTime
    {
        return clone $this->registeredAt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}
