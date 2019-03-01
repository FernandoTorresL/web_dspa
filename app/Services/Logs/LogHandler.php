<?php
/**
 * Created by PhpStorm.
 * Date: 01/03/2019
 * Time: 11:25 AM
 */

namespace App\Services\Logs;

use App\Events\Logs\LogMonologEvent;
use App\Track_aud;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class LogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level);
    }
    protected function write(array $record)
    {
        // Simple store implementation
        $log = new Track_aud();
        $log->fill($record['formatted']);
        //dd($log);
        $log->save();
// Queue implementation
// event(new LogMonologEvent($record));
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new LogFormatter();
    }
}
