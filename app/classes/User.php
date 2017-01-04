<?php

namespace app\classes;

use app\helpers\JWT;

/**
 * Description of User
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class User extends \yii\web\User
{
    private $_clientId;
    private $_client;

    public function getClientId()
    {
        return $this->_clientId;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function loginByAccessToken($token, $type = null)
    {
        if (($jwt = JWT::decode($token)) !== false) {
            $this->_clientId = $jwt['id'];
            
        }
        parent::loginByAccessToken($token, $type);
    }
}
