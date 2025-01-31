<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Contracts\DatabaseDriverInterface;

/**
 * Queue Class.
 *
 * This class is a blue-print for the queues
 *
 */
class Queue
{
    protected $dirver;

    public function __construct(DatabaseDriverInterface $driver)
    {
        $this->dirver = $driver;
    }

    /**
     * Push a job into a specific queue.
     * Serialize and store job
     *
     * @param mixed $job
     * @return void
     */
    public function push(Job $job)
    {
        $this->dirver->push($job);
    }

    /**
     * Pop a job from queue.
     * Retrieve and unserialize job
     *
     * @param mixed $job
     * @return void
     */
    public function pop($job)
    {
        $this->dirver->pop($job);
    }
}
