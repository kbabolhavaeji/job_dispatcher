<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Drivers\PDODriver;

class Dispatcher
{
    private ?Queue $queue = null;
    private const DEFAULT_QUEUE = 'default';
    protected string $defaultQueue = self::DEFAULT_QUEUE;

    /**
     * Get the value of queue
     */
    public function getQueue(): string
    {
        return $this->defaultQueue;
    }

    /**
     * Set the value of queue
     *
     * @param $queueName
     * @return  self
     */
    public function setQueue($queueName): static
    {
        $this->defaultQueue = $queueName;

        return $this;
    }

    /**
     * @param Job $job
     * @return void
     */
    public function dispatch(Job $job): void
    {
        $pdoDriverInstance = PDODriver::getInstance();
        $this->queue = new Queue($pdoDriverInstance);
        $this->queue->push($job);
    }
}