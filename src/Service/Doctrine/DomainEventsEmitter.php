<?php

namespace App\Service\Doctrine;

use App\Domain\Event\Event;
use App\Domain\Event\EventStore;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DomainEventsEmitter
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * DomainEventsEmitter constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     * @throws \Doctrine\ORM\ORMException
     */
    public function postFlush(PostFlushEventArgs $eventArgs): void
    {
        $em = $eventArgs->getEntityManager();
        $events = EventStore::release();

        if (empty($events)) {
            return;
        }

        $this->emitEvents($events);
        $em->flush();
    }

    /**
     * @param Event[] $events
     */
    private function emitEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event->getName(), $event);
        }
    }
}
