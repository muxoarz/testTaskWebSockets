<?php

require __DIR__ . '/vendor/autoload.php';

$longopts  = [
    "get-all-users",
    "get-all-user-task::",
    "send-message::",
    "send-task::",
    "message::"
];
$options = getopt('', $longopts);
if (!is_array($options) ) {
    help();
}
if (isset($options['get-all-users'])) {
    request('get-all-users');
} elseif (isset($options['get-all-user-task'])) {
    request('get-all-user-task', $options);
} elseif (isset($options['send-message'])) {
    request('send-message', $options);
} else {
    help();
}

/**
 * Request data
 * @param string $method
 * @param array  $params
 */
function request(string $method, array $params = [])
{
    \Ratchet\Client\connect('ws://localhost:8888/server')->then(function ($conn) use ($method, $params) {
        $conn->on('message', function ($msg) use ($conn) {
            echo "Received: {$msg}\n";
            $conn->close();
        });

        $conn->send(json_encode([
            'method' => $method,
            'params' => $params
        ]));
    }, function ($e) {
        echo "Could not connect: {$e->getMessage()}\n";
    });
}

/**
 * Shows help and exit
 */
function help()
{
    echo "
    There was a problem reading in the options.
    USAGE: php client.php [params]
    PARAMS:
    --get-all-users - Show all users
    --get-all-user-task=USER_ID  - Show user tasks
    --send-message=USER_ID - Send message to user or use 'all' for broadcast message
    --send-task=TASK_ID - Send message only to TASK_ID task, working only with --send-message param
    --message=TextMessage , working only with --send-message param
    ";
    exit(1);
}
