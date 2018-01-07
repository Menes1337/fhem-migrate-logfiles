<?php

namespace FhemMigrateLogfiles\Repository\Source;

use FhemMigrateLogfiles\Repository\ReadDevices;
use FhemMigrateLogfiles\Repository\SaveEvent;
use FhemMigrateLogfiles\ValueObject\Device;
use FhemMigrateLogfiles\Entity\Event;

class Mysql implements SaveEvent, ReadDevices
{
    /** @var \PDO */
    private $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Event[] $events
     * @throws SavingFailedException
     */
    public function saveEvent(array $events): void
    {
        try {
            $insertStatement = $this->pdo->prepare(
                "INSERT INTO `history` VALUES(:timestamp, :device, :deviceType, :event, :reading, :value, '');"
            );

            foreach ($events as $event) {
                $insertStatement->bindValue(":timestamp", $event->getTimestamp(), \PDO::PARAM_STR);
                $insertStatement->bindValue(":device", $event->getDevice()->getName(), \PDO::PARAM_STR);
                $insertStatement->bindValue(":deviceType", $event->getDevice()->getType(), \PDO::PARAM_STR);
                $insertStatement->bindValue(":event", $event->getEvent(), \PDO::PARAM_STR);
                $insertStatement->bindValue(":reading", $event->getReading(), \PDO::PARAM_STR);
                $insertStatement->bindValue(":value", $event->getValue(), \PDO::PARAM_STR);

                $insertStatement->execute();
            }
        } catch (\PDOException $PDOException) {
            throw new SavingFailedException($PDOException->getMessage());
        }
    }

    /**
     * @return Device[] array index is the device name
     * @throws ReadingFailedException
     */
    public function readDevices(): array
    {
        $devices = array();
        try {
            $selectStatement = $this->pdo->prepare(
                "SELECT `DEVICE`, `TYPE` FROM `current` GROUP BY `DEVICE`"
            );

            $selectStatement->execute();

            while ($row = $selectStatement->fetch(\PDO::FETCH_ASSOC)) {
                $devices[$row['DEVICE']] = new Device($row['DEVICE'], $row['TYPE']);
            }
        } catch (\PDOException $PDOException) {
            throw new ReadingFailedException($PDOException->getMessage());
        }

        return $devices;
    }
}