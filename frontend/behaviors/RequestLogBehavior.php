<?php

namespace Zelenin\yii\modules\RequestLog\behaviors;

use Yii;
use yii\base\Application;
use yii\base\Behavior;
use Zelenin\yii\modules\RequestLog\models\RequestLog;

class RequestLogBehavior extends Behavior
{
    /** @var array */
    public $excludeRules = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        $exclude = false;
        foreach ($this->excludeRules as $excludeRule) {
            if (call_user_func($excludeRule)) {
                $exclude = true;
                break;
            }
        }
        return $exclude ? [] : [Application::EVENT_AFTER_REQUEST => 'afterRequest'];
    }

    /**
     * @param $event
     */
    public function afterRequest($event)
    {
        $request = new RequestLog;
        $request->save();
    }
}
