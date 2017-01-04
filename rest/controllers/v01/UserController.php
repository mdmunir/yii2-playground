<?php

namespace rest\controllers\v01;

use Yii;
use rest\classes\Controller;
use app\models\form\Login;
use rest\models\Auth;
use app\models\form\Signup;

/**
 * Description of UserController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UserController extends Controller
{

    protected function verbs()
    {
        return[
            'login' => ['post'],
            'auth' => ['post'],
            'signup' => ['post'],
        ];
    }

    public function actionLogin()
    {
        $model = new Login();
        $model->load(Yii::$app->getRequest()->bodyParams, '');
        return $model->loginRest();
    }

    public function actionAuth()
    {
        $model = new Auth();
        $model->load(Yii::$app->getRequest()->bodyParams, '');
        $model->login();
        return $model;
    }

    public function actionSignup()
    {
        $model = new Signup();
        $model->load(Yii::$app->getRequest()->bodyParams, '');
        if (($user = $model->signup()) !== null) {
            Yii::$app->getUser()->getIdentity()->link('user', $user);
        }
        return $model;
    }

    public function actionInfo($source, $access_token)
    {
        /* @var $client \yii\authclient\clients\Facebook */
        $client = \Yii::$app->authClientCollection->getClient($source);
        $client->setAccessToken(['params' => ['access_token' => $access_token]]);
        return $client->getUserAttributes();
    }
}
