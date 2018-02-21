<?php

namespace Models\Handlers;

use Models\User;
use Models\UsersRepository;

abstract class AbstractHandler
{
    /**
     * @var UsersRepository
     */
    protected $usersRepo;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var array
     */
    protected $params;

    public function __construct(UsersRepository $usersRepo, User $messageOwner, array $params = [])
    {
        $this->usersRepo = $usersRepo;
        $this->user = $messageOwner;
        $this->params = $params;
    }

    abstract public function handle();

}
