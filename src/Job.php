<?php

namespace JobsQueueWorker;

use Exception;
use Throwable;

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

    protected int $attempts = self::ATTEMPTS;
    protected int $maxAttempts = self::MAXATTEMPTS;
    protected string $queue = self::DEFAULT_QUEUE;
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
     * @param  mixed $e
     * @return void
     */
    abstract public function fail();
    
    /**
     * incrementAttempts
     *
     * @return void
     */
    public function incrementAttempts()
    {
        $this->attempts++;
    }
    
    /**
     * hasExceededMaxAttempts
     *
     * @return void
     */
    public function hasExceededMaxAttempts()
    {
        return $this->attempts >= $this->maxAttempts;
    }
    
    /**
     * shouldRetry
     *
     * @return void
     */
    public function shouldRetry()
    {
        return !$this->hasExceededMaxAttempts();
    }
    
    /**
     * serialize
     *
     * @return void
     */
    public function serialize()
    {
        return serialize([
            'attempts' => $this->attempts,
            'queue' => $this->queue,
            'state' => $this->state
        ]);
    }
        
    /**
     * unserialize
     *
     * @param  mixed $data
     * @return void
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->attempts = $data['attempts'];
        $this->queue = $data['queue'];
        $this->state = $data['state'];
    }

    /**
     * Get the value of queue
     */ 
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set the value of queue
     *
     * @return  self
     */ 
    public function setQueue($queue)
    {
        $this->queue = $queue;

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
     * @return  self
     */ 
    public function setState($state)
    {
        $this->state = self::JOB_STATES[$state];

        return $this;
    }
}