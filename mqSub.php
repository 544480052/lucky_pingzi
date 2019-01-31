<?php
/**
 * Created by PhpStorm.
 * User: chengxi
 * Date: 2019/1/31
 * Time: 下午1:37
 */

require_once __DIR__ . "/vendor/autoload.php";


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = [
    "host"     => "127.0.0.1",
    "port"     => 5672,
    "user"     => "guest",
    "password" => "guest",
];

//创建链接
$connection = new AMQPStreamConnection($config["host"], $config["port"], $config["user"], $config["password"]);
$channel = $connection->channel();


//接收消息
$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
};
$channel->basic_consume('hello', 'test', false, false, false, false, $callback);

//监听消息
while (count($channel->callbacks)) {
    $channel->wait();
}
