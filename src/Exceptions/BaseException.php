<?php

namespace JobsQueueWorker\Exceptions;

use RuntimeException;

/**
 * BaseException
 */
abstract class BaseException extends RuntimeException {

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }
}