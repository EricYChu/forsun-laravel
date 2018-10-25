<?php

namespace Snower\LaravelForsun\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Snower\LaravelForsun\Jobs\ScheduleRunHandler;

class ScheduleRegisterCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'forsun:schedule:register';

    /**
     * @var string
     */
    protected $description = 'register schedule:run';

    /**
     * @return void
     */
    public function handle(): void
    {
        $forsun = Container::getInstance()->make('forsun');
        $name = ':schedule:run';
        $forsun->plan($name)->everyMinute()->job(new ScheduleRunHandler());
        $this->info("success");
    }
}