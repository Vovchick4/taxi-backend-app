<?php

require __DIR__ . '/vendor/autoload.php';

use Ratchet\App;
use App\Services\WebSocket\ChatOrder;

$server = new App('taxi-backend-app', 8080);
$server->route('/order-chat/{orderId}', new ChatOrder, ['*']);
$server->run();
