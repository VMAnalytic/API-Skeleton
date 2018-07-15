<?php

namespace App\DataFixtures\ORM;

use App\Service\Doctrine\DomainEventsEmitter;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture as Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractFixture extends Fixture implements ORMFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    protected function getReferencesByPattern(string $pattern)
    {
        $names = array_keys($this->referenceRepository->getReferences());
        $names = array_filter($names, function ($key) use ($pattern) {
            return 0 === strpos($key, $pattern);
        });

        $references = array_combine($names, array_map(function ($name) {
            return $this->getReference($name);
        }, $names));

        ksort($references);

        return $references;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param array $events
     */
    protected function removeDomainEventListeners(array $events): void
    {
        foreach ($this->getEventListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof DomainEventsEmitter) {
                    $this->getEntityManager()->getEventManager()->removeEventListener($events, $listener);
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function getEventListeners(): array
    {
        return $this->getEntityManager()
                    ->getEventManager()
                    ->getListeners();
    }

    /**
     * @param string $tableName
     * @param bool   $cascade
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function truncate(string $tableName, $cascade = true): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate(
            $platform->getTruncateTableSQL($tableName, $cascade)
        );
    }

    protected function getRandomElement(array $elements)
    {
        if (empty($elements)) {
            return null;
        }

        return $elements[array_rand($elements, 1)];
    }

    /**
     * @return string
     */
    public function getCurrentEnvironment(): string
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->container->get('kernel');
        return $kernel->getEnvironment();
    }
}
