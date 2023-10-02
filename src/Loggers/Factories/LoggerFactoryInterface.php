<?php

namespace ADB\WooCommerceStockTrack\Loggers\Factories;

interface LoggerFactoryInterface
{
    public function create($action);
}
