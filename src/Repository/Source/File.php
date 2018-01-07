<?php

namespace FhemMigrateLogfiles\Repository\Source;

use FhemMigrateLogfiles\Repository\ReadEvent;
use FhemMigrateLogfiles\ValueObject\Device;
use FhemMigrateLogfiles\Entity\Event;

class File implements ReadEvent
{
    const DATE = 'date';
    const TIME = 'time';
    const DEVICE = 'device';
    const EVENT = 'event';
    const READING = 'reading';
    const VALUE = 'value';

    /** @var \SplFileObject */
    private $file;

    /**
     * @param \SplFileObject $file
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    /**
     * @param Device[] $devices
     * @return Event
     * @throws ReadingFailedException
     */
    public function readNextEvent(array $devices): ?Event
    {
        if ($this->file->eof()) {
            return null;
        }

        $currentLine = $this->file->current();
        if (empty($currentLine)) {
            return null;
        }

        if (!preg_match(
            '/^(?P<' . self::DATE . '>[0-9]{4}\-[0-9]{2}\-[0-9]{2})_(?P<' . self::TIME . '>[0-9]{2}:[0-9]{2}:[0-9]{2}) (?P<' . self::DEVICE . '>\S*) (?P<' . self::EVENT . '>.*)/',
            $currentLine,
            $lineResult
        )) {
            throw new ReadingFailedException('Could not read log file entry');
        }

        $this->file->next();

        preg_match(
            '/(?P<' . self::READING . '>\S+): (?P<' . self::VALUE . '>.*)/',
            $lineResult['event'],
            $readingResult
        );

        if (!isset($devices[$lineResult[self::DEVICE]])) {
            throw new UnknownDeviceException('read event from unknown device');
        }

        return new Event(
            $devices[$lineResult[self::DEVICE]],
            $lineResult[self::DATE] . ' ' . $lineResult[self::TIME],
            $lineResult[self::EVENT],
            count($readingResult) == 0 ? 'state' : $readingResult[self::READING],
            count($readingResult) == 0 ? $lineResult[self::EVENT] : $readingResult[self::VALUE]
        );
    }
}