<?php

namespace Snower\LaravelForsun\Jobs;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Container\Container;

class ScheduleRunHandler implements ShouldQueue
{
    /**
     * @return void
     */
    public function handle(): void
    {
        /** @var Application $laravel */
        $laravel = Container::getInstance();

        /** @var Schedule $schedule */
        $schedule = $laravel->make(Schedule::class);

        foreach ($schedule->dueEvents($laravel) as $event) {
            if (!$event->filtersPass($laravel)) {
                continue;
            }
            $event->run($laravel);
        }
    }
}