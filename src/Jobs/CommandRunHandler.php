<?php

namespace Snower\LaravelForsun\Jobs;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\CacheEventMutex;

class CommandRunHandler implements ShouldQueue
{
    protected $mutex;

    /**
     * CommandRunHandler constructor.
     */
    public function __construct()
    {
        /** @var Application $container */
        $container = Container::getInstance();

        $this->mutex = $container->bound(EventMutex::class)
            ? $container->make(EventMutex::class)
            : $container->make(CacheEventMutex::class);
    }

    /**
     * @param string $command
     * @return void
     */
    public function handle(string $command): void
    {
        $event = new Event($this->mutex, $command);
        $event->run(Container::getInstance());
    }
}