<?php

namespace App\DataFixtures\ORM;

use App\Domain\Entity\User\UserBuilder;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public const FIXTURES_COUNT = 20;

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (range(1, self::FIXTURES_COUNT) as $i) {
            $builder = (new UserBuilder())
                        ->withEmail($this->faker->email)
                        ->withPassword($this->faker->password, $this->passwordEncoder())
            ;
            $user = $builder->build();
            $manager->persist($user);
            $this->setReference('user' . $i, $user);
        }
    }

    /**
     * @return PasswordEncoderInterface
     */
    public function passwordEncoder(): PasswordEncoderInterface
    {
        return new class() implements PasswordEncoderInterface{

            public function encodePassword($raw, $salt)
            {
                return md5($raw);
            }

            public function isPasswordValid($encoded, $raw, $salt)
            {
                return $encoded = md5($raw);
            }
        };
    }
}
