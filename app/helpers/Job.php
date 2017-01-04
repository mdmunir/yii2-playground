<?php

namespace app\helpers;

use Yii;

/**
 * Description of Job
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Job
{

    public static function sendEmail($data, $delay = 0)
    {
        return Yii::$app->queue->push('notive/send-mail', $data, $delay);
    }

    public static function fcmPush($data, $delay = 0)
    {
        Yii::$app->queue->push('notive/fcm-push', $data, $delay);
    }
}
