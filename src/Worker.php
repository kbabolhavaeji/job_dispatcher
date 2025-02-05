<?php

namespace JobsQueueWorker;

use Exception;
use JobsQueueWorker\Drivers\PDODriver;

class Worker
{
    private Queue $queue;
    private const DEFAULT_JOB_EXECUTION_METHOD_NAME = 'execute';
    private const DEFAULT_JOB_FAIL_METHOD_NAME = 'fail';

    /**
     * @param string $className
     * @param string $methodName
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    private function getReflectionMethod(string $className, string $methodName): \ReflectionMethod
    {
        return new \ReflectionMethod($className, $methodName);
    }

    public function __construct()
    {
        $pdoDriverInstance = PDODriver::getInstance();
        $this->queue = new Queue($pdoDriverInstance);
    }

    public function handle(string $queue = 'default'): void
    {
        $driver = PDODriver::getInstance();
        $query = $driver->builder($queue);

        while ($obj = $query->fetchObject()) {

            // get the instantiated class properties
            $jobProperties = unserialize($obj->job);
            $this->queue->patch('state', Job::JOB_STATES['inprogress'], $obj->id);

            try {

                if ($jobProperties['attempts'] >= $jobProperties['maxAttempts']) {
                    $this->queue->patch('state', Job::JOB_STATES['failed'], $obj->id);
                    return;
                }

                $method = $this->getReflectionMethod($obj->class, self::DEFAULT_JOB_EXECUTION_METHOD_NAME);
                $method->invoke(new $obj->class());
                $this->queue->patch('state', Job::JOB_STATES['done'], $obj->id);
                $this->queue->pop($obj->id);

            } catch (Exception $e) {

                //todo: use $e for the log description

                $method = $this->getReflectionMethod($obj->class, self::DEFAULT_JOB_FAIL_METHOD_NAME);
                $method->invoke(new $obj->class());
                $this->queue->patch('state', Job::JOB_STATES['failed'], $obj->id);
                $tries = intval($jobProperties['attempts']) + 1;
                $jobProperties['attempts'] = $tries;
                $this->queue->patch('job', serialize($jobProperties), $obj->id);
            }
        }
    }
}
