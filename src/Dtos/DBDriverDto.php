<?php

namespace JobsQueueWorker\Dtos;

use JobsQueueWorker\Exceptions\ValidationException;

class DBDriverDto
{
    private string $host;
    private string $port;
    private string $username;
    private string $password;
    private string $database;
    private string $charset;

    /**
     * Get the value of host
     */
    public function getHost()
    {
        if (empty($this->host)) {
            throw new ValidationException('Host address has not set');
        }

        return $this->host;
    }

    /**
     * Set the value of host
     *
     * @param $host
     * @return void
     */
    public function setHost($host): void
    {
        if (empty($host)) {
            throw new ValidationException('Host address can not be empty');
        }
        $this->host = $host;
    }

    /**
     * Get the value of port
     */
    public function getPort()
    {
        if (empty($this->port)) {
            throw new ValidationException('Port has not set');
        }

        return $this->port;
    }

    /**
     * Set the value of port
     *
     * @param $port
     * @return void
     */
    public function setPort($port): void
    {
        if (empty($port)) {
            throw new ValidationException('Port can not be empty');
        }

        $this->port = $port;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        if (empty($this->username)) {
            throw new ValidationException('Username has not set');
        }

        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param $username
     * @return void
     */
    public function setUsername($username): void
    {
        if (empty($username)) {
            throw new ValidationException('Username can not be empty');
        }

        $this->username = $username;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        if (empty($this->password)) {
            throw new ValidationException('Password has not set');
        }

        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param $password
     * @return void
     */
    public function setPassword($password): void
    {
        if (empty($password)) {
            throw new ValidationException('Password can not be empty');
        }

        $this->password = $password;
    }

    /**
     * Get the value of table
     */
    public function getDatabase()
    {
        if (empty($this->database)) {
            throw new ValidationException('Database name has not set');
        }

        return $this->database;
    }

    /**
     * Set the value of table
     *
     * @param $database
     * @return void
     */
    public function setDatabase($database): void
    {
        if (empty($database)) {
            throw new ValidationException('Database name can not be empty');
        }

        $this->database = $database;
    }

    /**
     * Get the value of charset
     */
    public function getCharset()
    {
        if (empty($this->charset)) {
            throw new ValidationException('Charset has not set');
        }

        return $this->charset;
    }

    /**
     * Set the value of charset
     *
     * @param string $charset
     * @return void
     */
    public function setCharset(string $charset): void
    {
        if (empty($charset)) {
            throw new ValidationException('Charset can not be empty');
        }

        $this->charset = $charset;
    }
}
