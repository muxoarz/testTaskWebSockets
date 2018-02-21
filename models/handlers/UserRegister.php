<?php

namespace Models\Handlers;


use Models\Task;

/**
 * Class UserRegister
 * @package Models\Handlers
 */
class UserRegister extends AbstractHandler
{
    public function handle()
    {
        $this->user->setId($this->params['user_id'])->setName('Random name '. rand(1,100));
        if (isset($this->params['task_id'])) {
            $task = new Task();
            $task->setId($this->params['task_id'])->setName('Random task name ' . rand(1,200));
            $this->user->setTasks([$task]);
        }
        $this->user->sendMessage("Registerged");
    }
}
