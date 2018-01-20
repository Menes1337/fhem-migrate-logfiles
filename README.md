# FHEM Migrate log files

This is a tool I created to migrate my text FHEM log files to a MySQL database.

## Project properties

Codestyle: PSR-1 (http://www.php-fig.org/psr/psr-1/) / PSR-2 (http://www.php-fig.org/psr/psr-2/)

Autoloading: PSR-4 (http://www.php-fig.org/psr/psr-4/)

Minimum PHP Version: 7.0

## Usage

At your own risk! I give no guarantee this is working correctly. It was working in my case. You are free to use this as template and [contribute](#contributing)

## Example 

    // Database definition
    $pdoMysql = new PDO('mysql:dbname=fhem;host=localhost;port=3306', '<Your Username>', '<Your Password>');
    $pdoMysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqlSource = new \FhemMigrateLogfiles\Repository\Source\Mysql($pdoMysql);
     
    // Log file definition
    $fileName = basename('<Your logFile>');
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

For a more complex example file see this file: [Import via console](src/import.php) 

## Contributing

You are welcome to contribute!

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Make sure the codestyle (PSR-1 / PSR-2) is applied to your changes, your code is PHP Unit tested and can be executed on PHP 5.6/7.0/7.1
4. Commit your changes (`git commit -am 'Add some feature'`))
5. Push to the branch (`git push origin my-new-feature`)
6. Create a new pull request
