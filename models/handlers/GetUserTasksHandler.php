<?php

namespace Models\Handlers;

/**
 * Class GetAllUsersHandler
 * @package Models\Handlers
 */
class GetUserTasksHandler extends AbstractHandler
{
    public function handle()
    {
        $a = [];
        if ($user = $this->usersRepo->getUserById(intval($this->params['get-all-user-task']))) {
            foreach ($user->getTasks() as $task) {
                $a[] = $task->toArray();
            }
            $this->user->sendMessage($a);
            return;
        }
        $this->user->sendMessage("User not found!");
    }
}
