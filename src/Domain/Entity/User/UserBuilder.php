<?php

namespace App\Domain\Entity\User;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class UserBuilder
 * @package App\Domain\Entity\User
 */
class UserBuilder
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $encodedPassword;

    /**
     * @param string $email
     * @return UserBuilder
     */
    public function withEmail(string $email): UserBuilder
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     * @param string|null $password
     * @param PasswordEncoderInterface $passwordEncoder
     * @return $this
     */
    public function withPassword(string $password, PasswordEncoderInterface $passwordEncoder)
    {
        $this->encodedPassword = $passwordEncoder->encodePassword($password, null);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getEncodedPassword(): ?string
    {
        return $this->encodedPassword;
    }

    /**
     * @return User
     */
    public function build(): User
    {
        return new User($this);
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return base64_encode(serialize([
            'email'           => $this->email,
            'encodedPassword' => $this->encodedPassword,
        ]));
    }
}
