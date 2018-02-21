<?php

namespace Models\Handlers;

/**
 * Class SendMessageHandler
 * @package Models\Handlers
 */
class SendMessageHandler extends AbstractHandler
{
    public function handle()
    {
        if (empty($this->params['message'])) {
            $this->user->sendMessage("No message!");
            return;
        }
        foreach ($this->usersRepo->getRegisteredUsers() as $registeredUser) {
            if ($this->params['send-message'] == 'all' || $this->params['send-message'] == $registeredUser->getId()) {
                // finding task id
                if (isset($this->params['send-task'])) {
                    foreach ($registeredUser->getTasks() as $task) {
                        if ($task->getId() == $this->params['send-task']) {
                            $registeredUser->sendMessage($this->params['message']);
                        }
                    }
                } else {
                    $registeredUser->sendMessage($this->params['message']);
                }
            }
        }
        $this->user->sendMessage('Message was sent');
    }
}
