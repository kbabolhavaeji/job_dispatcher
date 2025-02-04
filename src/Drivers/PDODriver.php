<?php

namespace JobsQueueWorker\Drivers;

use JobsQueueWorker\Contracts\DatabaseDriverInterface;
use JobsQueueWorker\Dtos\DBDriverDto;
use JobsQueueWorker\Exceptions\DBDriverException;
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
     * @return PDODriver|null
     */
    public static function getInstance(): ?PDODriver
    {

        if (is_null(self::$instance)) {
            return new self();
        }

        return self::$instance;
    }

    /**
     * __construct method
     *
     */
    private function __construct()
    {

        $datasourceDto = new DBDriverDto();
        $datasourceDto->setHost('172.17.0.2');
        $datasourceDto->setPort('3306');
        $datasourceDto->setDatabase('jobdispatcher');
        $datasourceDto->setUsername('root');
        $datasourceDto->setPassword('password');
        $datasourceDto->setCharset('utf8');

        $this->DBDto = $datasourceDto;
        $this->plug();
    }

    /**
     * @var string PUSH_QUERY
     */
    private const PUSH_QUERY = "INSERT INTO jobs (class, job, queue, state) VALUES (:class, :job, :queue, :state)";

    /**
     * push query
     *
     * @param mixed $job
     * @return bool
     */
    public function push(Job $job): bool
    {

        $class = get_class($job);
        $jobDetails = $job->serialize();
        $queue = $job->getQueue();
        $state = $job->getState();

        $query = $this->operator->prepare(self::PUSH_QUERY);
        $query->bindParam(':class', $class, PDO::PARAM_STR);
        $query->bindParam(':job', $jobDetails, PDO::PARAM_STR);
        $query->bindParam(':queue', $queue, PDO::PARAM_STR);
        $query->bindParam(':state', $state, PDO::PARAM_STR);
        $query->execute();
        return $this->operator->lastInsertId();

    }

    /**
     * @var string POP_QUERY
     */
    private const POP_QUERY = "DELETE FROM jobs WHERE id = :job_id";

    /**
     * pop method to delete the record from database.
     *
     * @param mixed $id
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

        if (!is_null($this->operator)) {
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
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->operator = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {

            if ($e->getCode() == 1049) {
                throw new DBDriverException('Unknown database: ' . $database);
            }

            if ($e->getCode() == '42S02') {
                throw new DBDriverException('Table jobs does not exist: ' . $database);
            }

            throw new DBDriverException($e->getMessage());
        }
    }

    private const FETCH_ALL_QUERY = "SELECT * FROM jobs WHERE queue = :queue AND state = :state";
    public function builder(string $queue = 'default', string $state = 'pending'): \PDOStatement
    {
        $query = $this->operator->prepare(self::FETCH_ALL_QUERY);
        $query->bindParam(':queue', $queue, PDO::PARAM_STR);
        $query->bindParam(':state', $state, PDO::PARAM_STR);
        $query->execute();
        return $query;
    }
}