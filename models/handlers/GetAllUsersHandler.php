<?php

namespace Models\Handlers;

/**
 * Class GetAllUsersHandler
 * @package Models\Handlers
 */
class GetAllUsersHandler extends AbstractHandler
{
    public function handle()
    {
        $a = [];
        foreach ($this->usersRepo->getRegisteredUsers() as $registeredUser) {
            $a[] = $registeredUser->toArray();
        }
        $this->user->sendMessage($a);
    }
}
