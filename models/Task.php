<?php

namespace Models;


use Models\Interfaces\SerializableInterface;

class Task implements SerializableInterface
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
     * @return mixed
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
     * Dump
     * @return array
     */
    public function toArray() : array
    {
        $a = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        return $a;
    }

}
