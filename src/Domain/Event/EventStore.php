<?php

namespace App\Domain\Event;

final class EventStore
{
    /**
     * @var array
     */
    private static $events = [];

    /**
     * @param Event $event
     */
    public static function remember(Event $event): void
    {
        self::$events[] = $event;
    }

    /**
     * @return array
     */
    public static function release(): array
    {
        [$events, self::$events] = [self::$events, []];

        return $events;
    }
}
