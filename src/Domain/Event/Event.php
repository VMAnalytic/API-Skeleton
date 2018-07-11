<?php

namespace App\Domain\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

abstract class Event extends SymfonyEvent
{
    public function getName(): string
    {
        $className = explode('\\', \get_class($this));

        return end($className);
    }
}
