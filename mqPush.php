<?php
/**
 * Created by PhpStorm.
 * User: chengxi
 * Date: 2019/1/31
 * Time: 下午1:36
 */

if (!function_exists('get_micro_time')) {

    function get_micro_time($num = 3)
    {
        return (int)str_replace(".", "", sprintf("%.{$num}f", microtime(true)));
    }

}


require_once __DIR__ . "/vendor/autoload.php";

$config = [
    "host"     => "127.0.0.1",
    "port"     => 5672,
    "vhost"    => "/",
    "login"    => "guest",
    "password" => "guest",
];

//创建链接
$connection = new AMQPConnection($config);
$connection->connect();

//创建频道
$channel = new AMQPChannel($connection);
//创建交换机
$exchange = new AMQPExchange($channel);
$exchange->setName("test");
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();

//创建队列
$queue = new AMQPQueue($channel);
$queue->setName("hello");
$queue->setFlags(AMQP_DURABLE);
$queue->declareQueue();
$queue->bind("test", "route_key");

//生产消息
$exchange->publish("消息~~~", "route_key");


//消费消息
function callback($envelope, $queue)
{
    $msg = $envelope->getBody();
    echo " [x] Received ", $msg, "\n";
}

while (true) {
    $queue->consume("callback");
}







