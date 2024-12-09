<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Contracts\DatabaseDriverInterface;
use JobsQueueWorker\Job;

/**
 * Queue Class.
 * 
 * This class is a blue-print for the queues
 * 
 */
class Queue
{
    protected $dirver;
    protected ?Job $jobData;
    
    public function __construct(DatabaseDriverInterface $driver)
    {
        $this->dirver = $driver;
    }
    
    /**
     * push
     *
     * @param  mixed $job
     * @return void
     */
    public function push(Job $job)
    {
        // Serialize and store job
        $this->jobData = $this->dirver->push($job);
    }
    
    /**
     * pop
     *
     * @param  mixed $job
     * @return void
     */
    public function pop($job)
    {
        // Retrieve and unserialize job
        $this->dirver->pop($job);
    }
}
