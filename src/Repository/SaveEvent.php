<?php

namespace FhemMigrateLogfiles\Repository;

use FhemMigrateLogfiles\Repository\Source\SavingFailedException;
use FhemMigrateLogfiles\Entity\Event;

interface SaveEvent
{
    /**
     * @param Event[] $events
     * @return void
     * @throws SavingFailedException
     */
    public function saveEvent(array $events): void;
}