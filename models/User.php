<?php

namespace Models;

use Models\Interfaces\SerializableInterface;
use Ratchet\ConnectionInterface;

class User implements SerializableInterface
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Task[]
     */
    private $tasks = [];
    /**
     * @var ConnectionInterface
     */
    private $webSocketConnection;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param array $tasks
     * @return self
     */
    public function setTasks(array $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function haveTaskId(int $id)
    {
        foreach ($this->getTasks() as $task) {
            if ($task->getId() == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getWebSocketConnection()
    {
        return $this->webSocketConnection;
    }

    /**
     * @param ConnectionInterface $webSocketConnection
     * @return self
     */
    public function setWebSocketConnection(ConnectionInterface $webSocketConnection): self
    {
        $this->webSocketConnection = $webSocketConnection;

        return $this;
    }

    /**
     * Send simple message
     * @param $message string|array
     */
    public function sendMessage($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
        $this->getWebSocketConnection()->send($message);
    }

    /**
     * Dump
     * @return array
     */
    public function toArray() : array
    {
        $a = [
            'id' => $this->id,
            'name' => $this->name,
            'tasks' => [],
        ];
        foreach ($this->getTasks() as $task) {
            $a['tasks'][] = $task->toArray();
        }

        return $a;
    }

}
