<?php

namespace rest\classes;

use Yii;
use yii\web\Response;
use yii\base\Behavior;

/**
 * Description of FilterResponse
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class FilterResponse extends Behavior
{
    public $responseMessage;

    public function events()
    {
        return[
            Response::EVENT_BEFORE_SEND => 'beforeSend'
        ];
    }

    public function beforeSend($event)
    {
        /* @var $response Response */
        $response = $event->sender;
        if ($response->format != Response::FORMAT_JSON) {
            return;
        }
        /* @var $device \app\models\ar\Device */
        $device = Yii::$app->getUser()->getIdentity();
        $data = $response->data;
        $isSuccess = $response->isSuccessful;
        $message = [
            'code' => $response->statusCode,
            'message' => !$isSuccess && isset($data['message']) ? $data['message'] : $response->statusText,
        ];
        $response->statusCode = 200;
        if ($device) {
            if ($user = $device->user) {
                $profile = $user->profile;
                $auth = [
                    'isLoggedIn' => true,
                    'user_id' => $user->id,
                    'name' => $user->username,
                    'fullname' => $profile->fullname,
                    'photoUrl' => $profile->avatarUrl,
                    'emailAddress' => $user->email,
                    'phoneNumber' => null,
                    'hasNotiveKey' => !empty($device->notive_key)
                ];
            } else {
                $auth = [
                    'isLoggedIn' => false,
                ];
            }
            $auth['token'] = $device->id;

            $response->data = [
                'auth' => $auth,
                'returnMessage' => $message,
                'data' => $isSuccess || defined('LOCAL_DEBUG') ? $data : null,
            ];
        } else {
            $response->data = [
                'auth' => new \stdClass(),
                'returnMessage' => $message,
                'data' => null,
            ];
        }
    }
}
