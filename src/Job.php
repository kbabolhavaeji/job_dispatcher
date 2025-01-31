<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Drivers\PDODriver;
use JobsQueueWorker\Dtos\DBDriverDto;

/**
 * Job Abstract Class.
 *
 * This class is a blue-print for the job classes which extend this class.
 *
 * @package JobsQueueWorker
 * @access public
 * @var int $attempts the number of legal attempts
 * @var int $maxAttempts the maximum try of a job
 *
 */
abstract class Job
{
    protected const ATTEMPTS = 0;
    protected const MAXATTEMPTS = 3;
    protected const JOB_STATES = [
        'pending' => self::PENDING_STATE,
        'inprogress' => self::INPROGRESS_STATE,
        'done' => self::DONE_STATE,
        'failed' => self::FAILED_STATE,
        'retry' => self::RETRY_STATE
    ];

    private const PENDING_STATE = 'pending';
    private const INPROGRESS_STATE = 'inprogress';
    private const DONE_STATE = 'done';
    private const FAILED_STATE = 'failed';
    private const RETRY_STATE = 'retry';
    private const DEFAULT_QUEUE = 'default';
    private ?Queue $queue = null;

    protected int $attempts = self::ATTEMPTS;
    protected int $maxAttempts = self::MAXATTEMPTS;
    protected string $defaultQueue = self::DEFAULT_QUEUE;
    protected string $state = self::JOB_STATES['pending'];

    /**
     * handle
     *
     * @return void
     */
    abstract public function handle();

    /**
     * fail
     *
     * @return void
     */
    abstract public function fail();

    /**
     * incrementAttempts
     *
     * @return void
     */
    public function incrementAttempts(): void
    {
        $this->attempts++;
    }

    /**
     * hasExceededMaxAttempts
     *
     * @return bool
     */
    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= $this->maxAttempts;
    }

    /**
     * shouldRetry
     *
     * @return bool
     */
    public function shouldRetry(): bool
    {
        return !$this->hasExceededMaxAttempts();
    }

    /**
     * serialize
     *
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            'attempts' => $this->attempts,
            'queue' => $this->defaultQueue,
            'state' => $this->state
        ]);
    }

    /**
     * unserialize
     *
     * @param mixed $data
     * @return void
     */
    public function unserialize($data): void
    {
        $data = unserialize($data);
        $this->attempts = $data['attempts'];
        $this->defaultQueue = $data['queue'];
        $this->state = $data['state'];
    }

    /**
     * Get the value of queue
     */
    public function getQueue()
    {
        return $this->defaultQueue;
    }

    /**
     * Set the value of queue
     *
     * @return  self
     */
    private function setQueue($queueName)
    {
        $this->defaultQueue = $queueName;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @param $state
     * @return  self
     */
    public function setState($state): static
    {
        $this->state = self::JOB_STATES[$state];

        return $this;
    }

    /**
     * @param string $queue
     * @return void
     */
    public function dispatch(string $queue = 'default'): void
    {
        $this->setQueue($queue);

        // should be inserted in config directory
        $datasourceDto = new DBDriverDto();
        $datasourceDto->setHost('172.17.0.2');
        $datasourceDto->setPort('3306');
        $datasourceDto->setDatabase('jobdispatcher');
        $datasourceDto->setUsername('root');
        $datasourceDto->setPassword('password');
        $datasourceDto->setCharset('utf8');

        $pdoDriverInstance = PDODriver::getInstance($datasourceDto);
        $this->queue = new Queue($pdoDriverInstance);
        $this->queue->push($this);
        // should be inserted in config directory

    }
}