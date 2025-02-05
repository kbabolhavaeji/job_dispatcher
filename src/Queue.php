<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Contracts\DatabaseDriverInterface;

/**
 * Queue Class.
 *
 * This class is a blue-print for the queues.
 *
 */
class Queue
{
    protected DatabaseDriverInterface $dirver;

    public function __construct(DatabaseDriverInterface $driver)
    {
        $this->dirver = $driver;
    }

    /**
     * Push a job into a specific queue.
     * Serialize and store job.
     *
     * @param mixed $job
     * @return void
     */
    public function push(Job $job): void
    {
        $this->dirver->push($job);
    }

    /**
     * Pop a job from queue.
     * Retrieve and unserialize job.
     *
     * @param mixed $job
     * @return void
     */
    public function pop(int $job): void
    {
        $this->dirver->pop($job);
    }

    /**
     * Parch a specific field in a job row.
     *
     * @param string $field
     * @param string $value
     * @param int $id
     * @return void
     */
    public function patch(string $field, string $value, int $id): void
    {
        $this->dirver->patch($field, $value, $id);
    }
}
