<?php
require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('application');
//$streamHandler = new Monolog\Handler\StreamHandler('/var/log/newrelic-infra/newrelic-infra.log', Monolog\Logger::WARNING);
$streamHandler = new StreamHandler('php://stdout', Logger::WARNING);
//JsonFormatter::BATCH_MODE_NEWLINES
$streamHandler->setFormatter(new \Monolog\Formatter\JsonFormatter());
$log->pushHandler($streamHandler);
$log->pushProcessor(function ($record) {
    $record['service_name'] = 'php-demo-service';
    $record['level'] = $record['level_name'];
    $record['global_event_timestamp'] = date_format($record['datetime'], 'c');
    $record['context']['channel'] = $record['channel'];
    $record['global_event_name'] = isset($record['context']["global_event_name"])
        ? $record['context']["global_event_name"]
        : null;
    unset($record['datetime']);
    unset($record['level_name']);
    unset($record['extra']);
    unset($record['channel']);
    unset($record['context']["global_event_name"]);
    return $record;
});

// add records to the log
$log->warning('Foo', ["global_event_name" => "CART_ADD_ITEM", "test" => true, "number" => 32]);
$log->emergency('Bar', ['hello' => 'world']);