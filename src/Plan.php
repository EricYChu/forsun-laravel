<?php

namespace Snower\LaravelForsun;

use Carbon\Carbon;
use Snower\LaravelForsun\Client\ForsunPlan;

class Plan
{
    /**
     * @var Forsun
     */
    protected $forsun;

    /**
     * @var ForsunPlan
     */
    protected $forsunPlan;

    /**
     * @var bool
     */
    protected $removed;

    /**
     * @param Forsun $forsun
     * @param ForsunPlan $forsunPlan
     * @param bool $removed
     */
    public function __construct(Forsun $forsun, ForsunPlan $forsunPlan, bool $removed = false)
    {
        $this->forsun = $forsun;
        $this->forsunPlan = $forsunPlan;
        $this->removed = $removed;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->forsunPlan->key;
    }

    /**
     * @return Carbon
     */
    public function getNextRunTime(): Carbon
    {
        return Carbon::createFromTimestamp($this->forsunPlan->next_time);
    }

    /**
     * @return bool
     * @throws Client\ForsunPlanError
     */
    public function remove(): bool
    {
        $this->forsun->remove($this->forsunPlan->key);
        $this->removed = true;
        return true;
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->removed;
    }
}