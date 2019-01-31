<?php
/**
 * Created by PhpStorm.
 * User: chengxi
 * Date: 2019/1/31
 * Time: 下午1:36
 */


require_once __DIR__ . "/vendor/autoload.php";


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$config = [
    "host"=>"127.0.0.1",
    "port"=>5672,
    "user"=>"",
    "password"=>"",
];

//创建链接
$connection = new AMQPStreamConnection($config["host"],$config["port"],$config["user"],$config["password"]);
$channel = $connection->channel();
//发送消息
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg,'','hello');

//$channel->queue_declare('hello', false, false, false, false);

