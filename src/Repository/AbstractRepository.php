<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $repo;

    /**
     * AbstractRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->getRepository();
    }

    /**
     * @return ObjectRepository
     */
    abstract protected function getRepository(): ObjectRepository;

    /**
     * @param $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids): array
    {
        return $this->repo->findBy(['id' => $ids]);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    /**
     * @param $object
     */
    public function detach($object): void
    {
        $this->em->detach($object);
    }

    /**
     * @param $object
     */
    public function persist($object): void
    {
        $this->em->persist($object);
    }

    /**
     * @param bool $withFlush
     */
    public function clear(bool $withFlush = true): void
    {
        if ($withFlush) {
            $this->em->flush();
        }
        $this->em->clear();
    }


    public function flush(): void
    {
        $this->em->flush();
    }
}
