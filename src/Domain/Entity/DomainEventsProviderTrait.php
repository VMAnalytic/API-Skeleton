<?php

namespace App\Domain\Entity;

use App\Domain\Event\Event;
use App\Domain\Event\EventStore;

trait DomainEventsProviderTrait
{
    /**
     * @param Event $event
     */
    protected function rememberThat(Event $event): void
    {
        EventStore::remember($event);
    }
}
