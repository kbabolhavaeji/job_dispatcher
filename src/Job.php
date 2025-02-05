<?php

namespace JobsQueueWorker;

use JobsQueueWorker\Drivers\PDODriver;

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
    public const JOB_STATES = [
        'pending' => self::PENDING_STATE,
        'inprogress' => self::INPROGRESS_STATE,
        'done' => self::DONE_STATE,
        'failed' => self::FAILED_STATE
    ];

    private const PENDING_STATE = 'pending';
    private const INPROGRESS_STATE = 'processing';
    private const DONE_STATE = 'done';
    private const FAILED_STATE = 'failed';
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
    abstract public function execute();

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
            'maxAttempts' => $this->maxAttempts,
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
        $this->maxAttempts = $data['maxAttempts'];
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
        $pdoDriverInstance = PDODriver::getInstance();
        $this->queue = new Queue($pdoDriverInstance);
        $this->queue->push($this);
    }
}