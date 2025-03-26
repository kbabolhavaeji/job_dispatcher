<?php

namespace JobsQueueWorker;

/**
 * Job Abstract Class.
 *
 * This class is a blue-print for the job classes which extends this class.
 *
 * @package JobsQueueWorker
 * @access public
 * @var int $attempts the number of legal attempts
 * @var int $maxAttempts the maximum try of a job
 *
 */
abstract class Job
{
    public const ATTEMPTS = 0;
    public const MAX_ATTEMPTS = 3;
    public const JOB_STATES = [
        self::PENDING_STATE,
        self::PROCESSING_STATE,
        self::DONE_STATE,
        self::FAILED_STATE
    ];

    public const PENDING_STATE = 'pending';
    public const PROCESSING_STATE = 'processing';
    public const DONE_STATE = 'done';
    public const FAILED_STATE = 'failed';

    private string $state = self::PENDING_STATE;
    private int $attempts = self::ATTEMPTS;
    private int $maxAttempts = self::MAX_ATTEMPTS;

    /**
     * handle method.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * fail method.
     *
     * @return void
     */
    abstract public function fail(): void;

    /**
     * Increment number of attempts.
     *
     * @return void
     */
    public function incrementAttempts(): void
    {
        $this->attempts++;
    }

    /**
     * hasExceededMaxAttempts condition.
     *
     * @return bool
     */
    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= $this->maxAttempts;
    }

    /**
     * ShouldRetry condition.
     *
     * @return bool
     */
    public function shouldRetry(): bool
    {
        return !$this->hasExceededMaxAttempts();
    }

    /**
     * Get the value of state.
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set the value of state.
     *
     * @param $state
     * @return  self
     */
    public function setState($state): static
    {
        $this->state = $state;
    }

    /**
     * Get attempts number.
     *
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * Set attempts number.
     *
     * @param int $attempts
     * @return void
     */
    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    /**
     * Get the maximum try of attempts.
     *
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * Set maximum number of attempts.
     *
     * @param int $maxAttempts
     * @return void
     */
    public function setMaxAttempts(int $maxAttempts): void
    {
        $this->maxAttempts = $maxAttempts;
    }
}