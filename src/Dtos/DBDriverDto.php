<?php

namespace JobsQueueWorker\Dtos;

/**
 * @todo this class should folow singelton pattern
 */
class DBDriverDto {

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
        return $this->host;
    }

    /**
     * Set the value of host
     *
     * @return  self
     */ 
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get the value of port
     */ 
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the value of port
     *
     * @return  self
     */ 
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of table
     */ 
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set the value of table
     *
     * @return  self
     */ 
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Get the value of charset
     */ 
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set the value of charset
     *
     * @return  self
     */ 
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

}
