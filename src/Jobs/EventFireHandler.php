<?php

namespace Snower\LaravelForsun\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Container\Container;

class EventFireHandler implements ShouldQueue
{
    /**
     * @param string|object $event
     * @param array $payload
     * @param bool $halt
     * @return void
     */
    public function handle($event, $payload = [], $halt = false): void
    {
        Container::getInstance()->make('events')->fire($event, $payload, $halt);
    }
}