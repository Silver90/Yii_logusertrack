Yii 2 Advanced Project Template
===============================

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-app-advanced/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-app-advanced/downloads.png)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-advanced)

The request_log.json is located frontend/web
    
frontend/view/layout/rabbitmq_json.php: sending data to rabbitmq a create local json


Yii2 request log module
Yii2 request log module.

Installation

Composer

The preferred way to install this extension is through Composer.

Either run

php composer.phar require zelenin/yii2-request-log-module "dev-master"
or add

"zelenin/yii2-request-log-module": "dev-master"
to the require section of your composer.json

Usage

Configure Request log module in config:

'modules' => [
    'request-log' => [
        'class' => Zelenin\yii\modules\RequestLog\Module::className(),
        // username attribute in your identity class (User)
        'usernameAttribute' => 'name'
    ]
]
Run:

php yii migrate --migrationPath=@Zelenin/yii/modules/RequestLog/migrations
Exclude rules

Write in bootstrap.php for excluding of some logs:

Yii::$container->set(\Zelenin\yii\modules\RequestLog\behaviors\RequestLogBehavior::className(), [
    'excludeRules' => [
        function () {
            list ($route, $params) = Yii::$app->getRequest()->resolve();
            return $route === 'debug/default/toolbar';
        }
    ]
]);

yii2-amqp
Yii2 extension enables you to use RabbitMQ queuing with native Yii2 syntax.

Installation

Via composer

$ php composer.phar require iviu96afa/yii2-amqp "dev-master"
Or add

"iviu96afa/yii2-amqp": "dev-master"
to the require section of your composer.json file.

Also, add the following

'amqp' => [
    'class' => 'iviu96afa\amqp\components\Amqp',
    'host' => '127.0.0.1',
    'port' => 5672,
    'user' => 'username',
    'password' => 'password',
    'vhost' => '/',
],
to the components section of your config.php file.

How to use

1- Sending:

    $exchange = 'exchange';
    $queue = 'queue';
    $dataArray = array('x', 'y', 'z');
    $message = serialize($dataArray);

    Yii::$app->amqp->declareExchange($exchange, $type = 'direct', $passive = false, $durable = true, $auto_delete = false);
    Yii::$app->amqp->declareQueue($queue, $passive = false, $durable = true, $exclusive = false, $auto_delete = false);
    Yii::$app->amqp->bindQueueExchanger($queue, $exchange, $routingKey = $queue);
    Yii::$app->amqp->publish_message($message, $exchange, $routingKey = $queue, $content_type = 'applications/json', $app_id = Yii::$app->name);
2- Receiving:

    set_time_limit(0);
    error_reporting(E_ALL);

    use iviu96afa\anqp\PhpAmqpLib\Connection\AMQPConnection;

    $exchange = 'exchange';
    $queue = 'queue';
    $consumer_tag = 'consumer_1';

    $conn = new AMQPConnection('localhost', 5672, 'username', 'password', '/');
    $ch = $conn->channel();
    $ch->exchange_declare($exchange, 'direct', false, true, false);
    $ch->queue_bind($queue, $exchange);

    function process_message($msg) {
        $body = unserialize($msg->body);
    }

    $ch->basic_consume($queue, $consumer_tag, false, false, false, false, 'process_message');

    function shutdown($ch, $conn) {
        $ch->close();
        $conn->close();
    }

    register_shutdown_function('shutdown', $ch, $conn);

    // Loop as long as the channel has callbacks registered
    while (count($ch->callbacks)) {
        $ch->wait();
    }
    
    
    
