<?php

namespace JobsQueueWorker\Exceptions;

use JobsQueueWorker\Exceptions\BaseException;

/**
 * DBDriverException
 */
class DBDriverException extends BaseException {

    private const DB_DRIVER_EXCEPTION_ERROR = 300;

    public function __construct(string $message)
    {
        parent::__construct($message, self::DB_DRIVER_EXCEPTION_ERROR);
    }

}