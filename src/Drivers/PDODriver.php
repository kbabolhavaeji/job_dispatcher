<?php

namespace JobsQueueWorker\Drivers;

use JobsQueueWorker\Contracts\DatabaseDriverInterface;
use JobsQueueWorker\Dtos\DBDriverDto;
use JobsQueueWorker\Job;
use PDO;

class PDODriver implements DatabaseDriverInterface {

    protected $operator = null;
    private static ?PDODriver $instance = null;
    private DBDriverDto $DBDto;
    
    /**
     * generate new instance of the class. 
     *
     * to implement singleton pattern in the class strucrure.
     * 
     * @param  mixed $DBDto
     * @return PDODriver
     */
    public static function getInstance(DBDriverDto $DBDto){

        if(is_null(self::$instance)){
            return new static($DBDto);
        }

        return self::$instance;
    }
    
    /**
     * __construct method
     *
     * @param  mixed $DBDto
     * @return void
     */
    private function __construct($DBDto)
    {
        $this->DBDto = $DBDto;
        $this->plug();
    }

    /**
     * @var string PUSH_QUERY
     */
    private const PUSH_QUERY = "INSERT INTO jobs (class, job, queue, state) VALUES (:class, :job, :queue, :state)";
        
    /**
     * push query
     *
     * @param  mixed $job
     * @return PDO
     */
    public function push(Job $job): bool
    {

        $class = get_class($job);
        $query = $this->operator->prepare(self::PUSH_QUERY);
        $query->bindParam(':class', $class, PDO::PARAM_STR);
        $query->bindParam(':job', $job->serialize(), PDO::PARAM_STR);
        $query->bindParam(':queue', $job->getQueue(), PDO::PARAM_STR);
        $query->bindParam(':state', $job->getState(), PDO::PARAM_STR);
        $result = $query->execute();
        $result = $this->operator->lastInsertId();

        return $result;
    }

    /**
     * @var string POP_QUERY
     */
    private const POP_QUERY = "DELETE FROM jobs WHERE :job_id = ?";
    
    /**
     * pop method to delete the record from database.
     *
     * @param  mixed $id
     * @return void
     */
    public function pop($id): void
    {
        $query = $this->operator->prepare(self::POP_QUERY);
        $query->bindParam(':job_id', $id, PDO::PARAM_INT);
        $query->execute();
        $query->fetch();
    }
       
    /**
     * plug the connection to the database.
     *
     * @return void
     */
    private function plug(): void
    {

        if(is_null($this->operator) == false){
            return;
        }

        $host = $this->DBDto->getHost();
        $database = $this->DBDto->getDatabase();
        $port = $this->DBDto->getPort();
        $username = $this->DBDto->getUsername();
        $password = $this->DBDto->getPassword();
        $charset = $this->DBDto->getCharset();

        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->operator = new PDO($dsn, $username, $password, $options);
    }

}