<?php

use Models\Handlers\GetAllUsersHandler;
use Models\Handlers\GetUserTasksHandler;
use Models\Handlers\SendMessageHandler;
use Models\Handlers\UserRegister;
use Models\User;
use Models\UsersRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';

/**
 * Server
 */
class MyServer implements MessageComponentInterface
{
    /**
     * @var UsersRepository
     */
    protected $usersRepo;

    protected $connections;

    protected $handlers = [
        "user-register" => UserRegister::class,
        "get-all-users" => GetAllUsersHandler::class,
        "get-all-user-task" => GetUserTasksHandler::class,
        "send-message" => SendMessageHandler::class,
    ];

    /**
     * MyServer constructor.
     */
    public function __construct()
    {
        $this->usersRepo = new UsersRepository();
    }

    /**
     * OnOpen connection handler
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->log("Connected.");
        $user = new User();
        $user->setWebSocketConnection($conn);
        $this->usersRepo->add($user);
    }

    /**
     * OnMessage event handler
     * @param ConnectionInterface $from
     * @param string              $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $user = $this->usersRepo->searchByWebSocketConnection($from);
        $this->log("Received message: " . $msg);
        $msg = json_decode($msg, true);
        if ($msg === false || !isset($msg['method']) || !isset($this->handlers[$msg['method']])) {
            $user->sendMessage("Method not found or json incorrect");
            return;
        }
        /** @var \Models\Handlers\AbstractHandler $handler */
        $handler = new $this->handlers[$msg['method']] ($this->usersRepo, $user, isset($msg['params']) ? $msg['params'] : []);
        $handler->handle();
    }

    /**
     * OnClose event handler
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        if ($user = $this->usersRepo->searchByWebSocketConnection($conn)) {
            $this->usersRepo->remove($user);
        }
    }

    /**
     * Error event handler
     * @param ConnectionInterface $conn
     * @param Exception           $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * Logger
     * @param $text
     */
    private function log($text)
    {
        echo $text."\n";
    }
}

// Run the server application through the WebSocket protocol on port 8888
$app = new Ratchet\App('localhost', 8888);
$app->route('/server', new MyServer());
$app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
$app->run();
