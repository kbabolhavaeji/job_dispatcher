<?php

namespace JobsQueueWorker\Exceptions;

use RuntimeException;

/**
 * BaseException
 */
abstract class BaseException extends RuntimeException {

    protected $message;
    protected $code;

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }
}