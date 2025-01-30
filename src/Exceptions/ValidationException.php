<?php

namespace JobsQueueWorker\Exceptions;

use RuntimeException;
use function MongoDB\BSON\toJSON;

/**
 * DBDriverException
 */
class ValidationException extends RuntimeException {

    private const DB_DRIVER_EXCEPTION_ERROR_NUMBER = 402;

    public function __construct(string $message)
    {
        parent::__construct($message, self::DB_DRIVER_EXCEPTION_ERROR_NUMBER);
    }
}