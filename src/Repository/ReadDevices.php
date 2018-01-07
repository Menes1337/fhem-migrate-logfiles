<?php

namespace FhemMigrateLogfiles\Repository;

use FhemMigrateLogfiles\Repository\Source\ReadingFailedException;
use FhemMigrateLogfiles\ValueObject\Device;

interface ReadDevices
{
    /**
     * @return Device[]
     * @throws ReadingFailedException
     */
    public function readDevices(): array;
}