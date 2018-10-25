<?php

namespace Snower\LaravelForsun;

use Illuminate\Support\Collection;
use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;
use Snower\LaravelForsun\Client\ForsunClient;
use Snower\LaravelForsun\Client\ForsunPlanError;

class Forsun
{
    /**
     * @var Collection
     */
    protected $config;

    /**
     * @var TSocket
     */
    protected $socket = null;

    /**
     * @var TBufferedTransport
     */
    protected $transport = null;

    /**
     * @var TBinaryProtocol
     */
    protected $protocol = null;

    /**
     * @var ForsunClient
     */
    protected $client = null;

    /**
     * @param mixed $config
     */
    public function __construct($config)
    {
        $this->config = new Collection($config);
    }

    /**
     * @return ForsunClient
     * @throws ForsunPlanError
     */
    protected function getClient(): ForsunClient
    {
        if ($this->client == null) {
            $this->makeClient();
        }

        if ($this->socket == null || !$this->socket->isOpen()) {
            $this->makeClient();
        }

        if (empty($this->client)) {
            throw new ForsunPlanError([
                'code' => -1,
                'message' => 'network error'
            ]);
        }

        return $this->client;
    }

    /**
     * @return void
     */
    protected function makeClient(): void
    {
        $this->socket = new TSocket($this->config->get('host', '127.0.0.1'), $this->config->get("port", 6458));
        $this->transport = new TBufferedTransport($this->socket, 1024, 1024);
        $this->protocol = new TBinaryProtocol($this->transport);

        $this->socket->setSendTimeout(5000);
        $this->socket->setRecvTimeout(120000);
        $this->transport->open();

        $this->client = new ForsunClient($this->protocol);
    }

    /**
     * @return int
     * @throws ForsunPlanError
     */
    public function ping()
    {
        return $this->getClient()->ping();
    }

    /**
     * @param string $key
     * @param int $second
     * @param int $minute
     * @param int $hour
     * @param int $day
     * @param int $month
     * @param int $week
     * @param string $action
     * @param array $params
     * @return Plan
     * @throws ForsunPlanError
     */
    public function create(string $key, int $second, int $minute, int $hour, int $day, int $month, int $week, string $action, array $params): Plan
    {
        $forsunPlan = $this->getClient()->create($key, $second, $minute, $hour, $day, $month, $week, $action, $params);
        return new Plan($this, $forsunPlan);
    }

    /**
     * @param string $key
     * @param int $second
     * @param int $minute
     * @param int $hour
     * @param int $day
     * @param int $month
     * @param int $week
     * @param int $count
     * @param string $action
     * @param array $params
     * @return Plan
     * @throws ForsunPlanError
     */
    public function createTimeout(string $key, int $second, int $minute, int $hour, int $day, int $month, int $week, int $count, $action, array $params): Plan
    {
        $forsunPlan = $this->getClient()->createTimeout($key, $second, $minute, $hour, $day, $month, $week, $count, $action, $params);
        return new Plan($this, $forsunPlan);
    }

    /**
     * @param string $key
     * @return Plan
     * @throws ForsunPlanError
     */
    public function remove(string $key): Plan
    {
        $forsunPlan = $this->getClient()->remove($key);
        return new Plan($this, $forsunPlan, true);
    }

    /**
     * @param string $key
     * @return Plan
     * @throws ForsunPlanError
     */
    public function get(string $key): Plan
    {
        $forsunPlan = $this->getClient()->get($key);
        return new Plan($this, $forsunPlan);
    }

    /**
     * @return array
     * @throws ForsunPlanError
     */
    public function getCurrent(): array
    {
        $forsunPlans = $this->getClient()->getCurrent();
        $plans = [];
        foreach ($forsunPlans as $forsunPlan) {
            $plans[] = new Plan($this, $forsunPlan);
        }
        return $plans;
    }

    /**
     * @param string|int $timestamp
     * @return array
     * @throws ForsunPlanError
     */
    public function getTime($timestamp): array
    {
        $forsunPlans = $this->getClient()->getTime($timestamp);
        $plans = [];
        foreach ($forsunPlans as $forsunPlan) {
            $plans[] = new Plan($this, $forsunPlan);
        }
        return $plans;
    }

    /**
     * @param string $prefix
     * @return string[]
     * @throws ForsunPlanError
     */
    public function getKeys(string $prefix)
    {
        return $this->getClient()->getKeys($prefix);
    }

    /**
     * @param null|string $name
     * @return Builder
     */
    public function plan($name = null): Builder
    {
        return new Builder($this, $name);
    }
}