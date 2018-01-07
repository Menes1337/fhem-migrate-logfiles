<?php

/**
 * use: php import.php path reading
 *
 *      path:       file path to the FHEM log file you would like to convert
 *      reading:    optional parameter which will overwrite the name of the reading.
 *                  Useful when you have a simple log file containing just one value
 */

require(__DIR__ . '/../vendor/autoload.php');

if (!isset($argv[1])) {
    die('please pass a path via the first command line argument');
}

$pathToLogFile = $argv[1];
if (isset($argv[2])) {
    $overwriteReading = $argv[2];
}

$pdoMysql = new PDO('mysql:dbname=fhem;host=localhost;port=3306', '<Your Username>', '<Your Password>');
$pdoMysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$mysqlSource = new \FhemMigrateLogfiles\Repository\Source\Mysql($pdoMysql);

$fileName = basename($pathToLogFile);
$fileSource = new \FhemMigrateLogfiles\Repository\Source\File(new SplFileObject($pathToLogFile)) or die('Could not open file: ' . $pathToLogFile);

try {
    $devices = $mysqlSource->readDevices();

    $events = [];
    do {
        try {
            if (($event = $fileSource->readNextEvent($devices)) === null) {
                break;
            }

            $events[] = $event;
        } catch (\FhemMigrateLogfiles\Repository\Source\ReadingFailedException $readingFailedException) {
            die($readingFailedException->getMessage());
        }
    } while (!empty($event));

    $mysqlSource->saveEvent($events);
} catch (\FhemMigrateLogfiles\Repository\Source\ReadingFailedException $readingFailedException) {
    die($readingFailedException->getMessage());
} catch (\FhemMigrateLogfiles\Repository\Source\SavingFailedException $savingFailedException) {
    die($savingFailedException->getMessage());
}