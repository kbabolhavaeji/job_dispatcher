<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Queue;
use Exception;

class Worker
{
    protected $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function work()
    {
        // while ($job = $this->queue->pop()) {
        //     try {
        //         $job->handle();
        //     } catch (Exception $e) {
        //         $job->incrementAttempts();
        //         if ($job->shouldRetry()) {
        //             $this->queue->push($job);
        //         } else {
        //             $job->fail($e);
        //         }
        //     }
        // }
    }
}
