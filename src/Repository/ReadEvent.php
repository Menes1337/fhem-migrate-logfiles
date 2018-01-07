<?php

namespace FhemMigrateLogfiles\Repository;

use FhemMigrateLogfiles\Repository\Source\ReadingFailedException;
use FhemMigrateLogfiles\ValueObject\Device;
use FhemMigrateLogfiles\Entity\Event;

interface ReadEvent
{
    /**
     * @param Device[] $devices
     * @return Event|null
     * @throws ReadingFailedException
     */
    public function readNextEvent(array $devices): ?Event;
}