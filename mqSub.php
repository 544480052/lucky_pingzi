<?php
/**
 * Created by PhpStorm.
 * User: chengxi
 * Date: 2019/1/31
 * Time: 下午1:37
 */

require_once __DIR__ . "/vendor/autoload.php";

$config = [
    "host"     => "127.0.0.1",
    "port"     => 5672,
    "vhost"    => "/",
    "login"    => "cx",
    "password" => "123456",
];

//创建链接
$connection = new AMQPConnection($config);
$connection->connect();

//创建频道
$channel = new AMQPChannel($connection);


//创建队列
$queue = new AMQPQueue($channel);
$queue->setName("hello");


while (true) {
    $queue->consume("mq::callback");
}


class mq
{

    //消费消息
    static public function callback($envelope, $queue)
    {
        $msg = $envelope->getBody();
        $envelopeID = $envelope->getDeliveryTag();
        var_dump(json_decode($msg));
        $queue->ack($envelopeID);//通知服务端,消息已正确处理,可以删除消息
    }


}

