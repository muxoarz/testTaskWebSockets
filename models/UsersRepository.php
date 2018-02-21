<?php

namespace Models;


use Models\Interfaces\SerializableInterface;
use Ratchet\ConnectionInterface;

class UsersRepository implements SerializableInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected $users;

    /**
     * UsersRepository constructor.
     */
    public function __construct()
    {
        $this->users = new \SplObjectStorage();
    }

    /**
     * Add user to the repo
     * @param User $user
     */
    public function add(User $user)
    {
        $this->users->attach($user);
    }

    /**
     * Remove...
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->users->detach($user);
    }

    /**
     * Search by connection
     * @param ConnectionInterface $conn
     * @return User|null
     */
    public function searchByWebSocketConnection(ConnectionInterface $conn)
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($conn === $user->getWebSocketConnection()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Return array with all registered users
     * @return User[]
     */
    public function getRegisteredUsers()
    {
        $a = [];
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->getId() !== null) {
                $a[] = $user;
            }
        }

        return $a;
    }

    /**
     * @param int $id
     * @return User|null|object
     */
    public function getUserById(int $id)
    {
        /** @var User $user */
        foreach ($this->users as $user) {
            if ($user->getId() == $id) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $a = [];
        /** @var User $user */
        foreach ($this->users as $user) {
            $a[] = $user->toArray();
        }

        return $a;
    }
}
