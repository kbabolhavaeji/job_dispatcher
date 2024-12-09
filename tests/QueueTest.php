<?php

use PHPUnit\Framework\TestCase;
use JobsQueueWorker\Queue;
use JobsQueueWorker\Job;

class QueueTest extends TestCase
{
    public function testPushAndPopJob()
    {
        $queue = new Queue();
        $job = $this->getMockForAbstractClass(Job::class);
        $queue->push($job);
        $this->assertSame($job, $queue->pop());
    }
}
