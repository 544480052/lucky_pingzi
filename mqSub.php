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
    "user"     => "",
    "password" => "",
];

//创建链接
$connection = new AMQPStreamConnection($config["host"], $config["port"], $config["user"], $config["password"]);

$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
};
$channel->basic_consume('hello', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}
