<?php

namespace JobsQueueWorker\Contracts;

use JobsQueueWorker\Job;

Interface DatabaseDriverInterface {
        
    /**
     * store a job in the job table.
     * 
     * @param  mixed $jobDetails
     * @return void
     */
    public function push(Job $job): bool;
    
    /**
     * pop a job from job table.
     *
     * @return void
     */
    public function pop($id): void;
}