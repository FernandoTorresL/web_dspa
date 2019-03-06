<?php
/**
 * Created by PhpStorm.
 * Date: 01/03/2019
 * Time: 01:15 PM
 */

namespace App\Events\Logs;

use Illuminate\Queue\SerializesModels;

class LogMonologEvent
{
    use SerializesModels;
    /**
     * @var
     */
    public $records;
    /**
     * @param $model
     */
    public function __construct(array $records)
    {
        $this->records = $records;
    }
}
