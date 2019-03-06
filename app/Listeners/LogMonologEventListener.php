<?php
/**
 * Created by PhpStorm.
 * Date: 01/03/2019
 * Time: 01:17 PM
 */

namespace App\Listeners;

use App\Events\Logs\LogMonologEvent;
//use App\Models\Log;
use App\Track_aud;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogMonologEventListener implements ShouldQueue
{
    public $queue = 'logs';

    protected $log;

    //public function __construct(Log $log) {
    public function __construct(Track_aud $log) {
        $this->log = $log;
    }
    /**
     * @param $event
     */
    public function onLog($event)
    {
        $log = new $this->log;
        $log->fill($event->records['formatted']);
        $log->save();
    }
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            LogMonologEvent::class,
            'App\Listeners\LogMonologEventListener@onLog'
        );
    }
}
