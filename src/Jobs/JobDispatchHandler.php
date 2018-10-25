<?php

namespace Snower\LaravelForsun\Jobs;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobDispatchHandler implements ShouldQueue
{
    /**
     * @param string|ShouldQueue|Job $job
     * @return void
     */
    public function handle($job): void
    {
        dispatch(is_string($job) ? resolve($job) : $job);
    }
}