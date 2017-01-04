<?php

namespace rest\controllers;

use Yii;
use rest\classes\Controller;
use app\helpers\Job;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DefaultController extends Controller
{
    protected $authExcept = ['info', 'payload'];

    protected function verbs()
    {
        return [
            'payload' => ['post'],
            'synchron-travel-search' => ['post'],
            'broadcast' => ['post']
        ];
    }

    public function actionIndex()
    {
        return 'Piknikio App';
    }

    public function actionPayload()
    {
        $request = Yii::$app->getRequest();
        if ($request->getHeaders()->get('X-Gitlab-Token') != Yii::$app->params['gitlab.webhook.token']) {
            return false;
        }
        if ($request->getBodyParam('event_name') != 'push') {
            return false;
        }

        $states = Yii::$app->cache->get('commit-state');
        $commits = $request->getBodyParam('commits', []);
        if (!empty($states)) {
            $commits = array_merge($states, $commits);
        }
        Yii::$app->cache->set('commit-state', $commits);
        $log = Yii::getAlias('@runtime/scheduler') . date('/Ym/d') . '.log';
        \yii\helpers\FileHelper::createDirectory(dirname($log), 0777);
        file_put_contents($log, 'Time payload push :' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    }

    public function actionBroadcast()
    {
        $ids = \app\models\ar\Device::find()
            ->select(['notive_key'])
            ->where(['NOT', ['notive_key' => null]])
            ->column();
        if (count($ids)) {
            Job::fcmPush([$ids, Yii::$app->getRequest()->post()]);
        }
    }
}
