<?php

namespace JobsQueueWorker\Contracts;

use JobsQueueWorker\Job;

Interface DatabaseDriverInterface {

    /**
     * store a job in the job table.
     *
     * @param Job $job
     * @return bool
     */
    public function push(Job $job): bool;

    /**
     * pop a job from job table.
     *
     * @param $id
     * @return void
     */
    public function pop($id): void;

    /**
     * Fetch all the jobs for a queue
     *
     * @return \PDOStatement
     */
    public function builder(): \PDOStatement;


}