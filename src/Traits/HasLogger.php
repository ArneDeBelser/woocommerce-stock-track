<?php

namespace ADB\WooCommerceStockTrack\Traits;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait HasLogger
{
    public $log;

    public function __construct()
    {
        $this->log = new Logger('debug_logger');
        $this->log->pushHandler(new StreamHandler('debug.log'));
    }
}
