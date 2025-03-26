<?php

namespace JobsQueueWorker;

use Exception;
use JobsQueueWorker\Drivers\PDODriver;

class Worker
{
    /** @var Queue $queue */
    private Queue $queue;

    public function __construct()
    {
        $pdoDriverInstance = PDODriver::getInstance();
        $this->queue = new Queue($pdoDriverInstance);
    }

    /**
     * Execute handle method of the job.
     *
     * @param string $queue
     * @return void
     */
    public function execute(string $queue = 'default'): void
    {
        $driver = PDODriver::getInstance();
        $query = $driver->builder($queue);
        while ($obj = $query->fetchObject()) {
            /** @var Job $jobObject */
            $jobObject = unserialize($obj->job);
            try {
                $this->queue->patch('state', Job::PROCESSING_STATE, $obj->id);
                if ($jobObject->hasExceededMaxAttempts()) {
                    $this->queue->patch('state', Job::FAILED_STATE, $obj->id);
                    return;
                }
                $jobObject->handle();
                $this->queue->patch('state', Job::DONE_STATE, $obj->id);
                $this->queue->pop($obj->id);
            } catch (Exception $e) {
                $jobObject->fail();
                $this->queue->patch('state', Job::FAILED_STATE, $obj->id);
                $jobObject->incrementAttempts();
                $jobObject->setState(Job::FAILED_STATE);
                $this->queue->patch('job', serialize($jobObject), $obj->id);
            }
        }
    }
}