<?php

namespace task\controllers;

use Yii;
use dee\queue\WorkerController;
use yii\helpers\ArrayHelper;

/**
 * Description of NotiveController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class NotiveController extends WorkerController
{

    public function actionFcmPush()
    {
        $params = $this->getActionParams();
        list($ids, $data) = $params;
        $retry = isset($params[2]) ? $params[2] : null;

        $result = Yii::$app->notification->fcmSend($ids, $data, $retry);
        if (YII_DEBUG) {
            echo json_encode($result)."\n";
        }
        return true;
    }

    public function actionPusherPush()
    {
        list($channel, $event, $data) = $this->getActionParams();
        $result = Yii::$app->notification->pusherSend($channel, $event, $data);
        if (YII_DEBUG) {
            echo json_encode($result)."\n";
        }
        return true;
    }

    public function actionSendMail()
    {
        $data = $this->getActionParams();
        $view = ArrayHelper::remove($data, 'view');
        $params = ArrayHelper::remove($data, 'params', []);
        $message = Yii::$app->mailer->compose($view, $params);
        foreach ($data as $key => $value) {
            $message->$key = $value;
        }
        return $message->send();
    }
}
