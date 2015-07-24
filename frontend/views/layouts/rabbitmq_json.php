<?php

$db = new PDO('mysql:dbname=yii2advanced;host=localhost;', 'root', '');
//here prepare the query for analyzing, prepared statements use less resources and thus run faster
$row = $db->prepare('select * from request_log order by datetime DESC');

$row->execute();//execute the query
$json_data = array();//create the array
foreach ($row as $rec)//foreach loop
{
    $json_array['id'] = $rec['id'];
    $json_array['app_id'] = $rec['app_id'];
    $json_array['route'] = $rec['route'];
    $json_array['params'] = $rec['params'];
    $json_array['user_id'] = $rec['user_id'];
    $json_array['ip'] = $rec['ip'];
    $json_array['datetime'] = $rec['datetime'];
    $json_array['user_agent'] = $rec['user_agent'];
    //here pushing the values in to an array
    array_push($json_data, $json_array);

}
//built in PHP function to encode the data in to JSON format
file_put_contents("request_log.json", json_encode($json_data, JSON_PRETTY_PRINT));
$exchange = 'exchange';
$queue = 'queue';
$message = serialize($json_data);

Yii::$app->amqp->declareExchange($exchange, $type = 'direct', $passive = false, $durable = true, $auto_delete = false);
Yii::$app->amqp->declareQueue($queue, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
Yii::$app->amqp->bindQueueExchanger($queue, $exchange, $routingKey = $queue);
Yii::$app->amqp->publish_message($message, $exchange, $routingKey = $queue, $content_type = 'applications/json', $app_id = Yii::$app->name);
