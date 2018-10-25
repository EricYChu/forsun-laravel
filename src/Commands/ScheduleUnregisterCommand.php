<?php

namespace Snower\LaravelForsun\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class ScheduleUnregisterCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'forsun:schedule:unregister';

    /**
     * @var string
     */
    protected $description = 'unregister schedule:run';

    /**
     * @return void
     */
    public function handle(): void
    {
        $forsun = Container::getInstance()->make('forsun');
        $name = config('forsun.prefix') . ':schedule:run';
        $forsun->remove($name);
        $this->info("success");
    }
}