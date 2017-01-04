<?php

namespace app\classes;

use Yii;
use yii\httpclient\Client;
use app\helpers\Job;
use Pusher;

/**
 * Description of Notification
 *
 * @property Pusher $pusher
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Notification
{
    /**
     *
     * @var Pusher
     */
    private $_pusher;

    /**
     *
     * @return Pusher
     */
    public function getPusher()
    {
        if ($this->_pusher === null) {
            $params = Yii::$app->params;
            $this->_pusher = new Pusher($params['pusher.key'], $params['pusher.secret'], $params['pusher.app_id']);
        }
        return $this->_pusher;
    }

    public function fcmSend(array $ids, $data = [], $retry = null)
    {
        $data['registration_ids'] = $ids;
        $key = Yii::$app->params['fcm.key'];
        $response = (new Client())->post('https://fcm.googleapis.com/fcm/send', $data, ['Authorization' => "key={$key}"])
            ->setFormat('json')
            ->send();
        if (!$response->getIsOk()) {
            return false;
        }

        $results = $response->getData();
        $r = [];
        $updateIds = [];
        $deletes = [];
        $retries = [];
        if (isset($results['results'])) {
            foreach ($results['results'] as $i => $result) {
                $oldId = $ids[$i];
                $message = 'Success';
                if (isset($result['registration_id'])) {
                    $updateIds[$oldId] = $result['registration_id'];
                }
                if (isset($result['error'])) {
                    switch ($result['error']) {
                        case 'NotRegistered':
                            $deletes[] = $oldId;
                            unset($updateIds[$oldId]);
                            break;

                        case 'Unavailable':
                            if (isset($updateIds[$oldId])) {
                                $retries[] = $updateIds[$oldId];
                            } else {
                                $retries[] = $oldId;
                            }
                            break;
                        default:
                            break;
                    }
                    $message = $result['error'];
                }

                $r[] = ['id' => $oldId, 'message' => $message];
            }
        }

        if (count($retries)) {
            unset($data['registration_ids']);
            $retry = $retry === null ? 60 : 2 * $retry;
            if ($retry < 86400) {
                if (isset($data['time_to_live'])) {
                    $data['time_to_live'] -= $retry;
                }
                if (!isset($data['time_to_live']) || $data['time_to_live'] > 0) {
                    Job::fcmPush([$retries, $data, $retry], $retry);
                }
            }
        }

        $command = Yii::$app->db->createCommand();
        // update new key
        foreach ($updateIds as $old => $new) {
            $command->update('device', ['notive_key' => $new], ['notive_key' => $old])->execute();
        }

        // delete unregistered key. Entar dulu
        if (count($deletes)) {
            $command->update('device', ['notive_key' => null], ['notive_key' => $deletes])->execute();
        }

        return $r;
    }

    public function pusherSend($channel, $event, $data = [])
    {
        return $this->getPusher()->trigger($channel, $event, $data);
    }
}
