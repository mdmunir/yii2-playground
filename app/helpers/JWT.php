<?php

namespace app\helpers;

use Yii;
use yii\helpers\Json;

/**
 * Description of JWT
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class JWT
{

    protected static function getKey()
    {
        return Yii::$app->params['app.secretKey'] . '::jwt';
    }

    public static function decode($input)
    {
        if (($m = strlen($input) % 4) > 0) {
            $input .= str_repeat('=', 4 - $m);
        }
        $input = base64_decode(strtr($input, '-_', '+/'));
        $input = Yii::$app->security->validateData($input, static::getKey());
        if ($input === false) {
            return false;
        }
        list($output, $expire) = Json::decode($input);
        if ($expire == 0 || $expire > time()) {
            return $output;
        }
        return false;
    }

    public static function encode($input, $expire = 0)
    {
        $input = Json::encode([$input, $expire > 0 ? $expire + time() : 0]);
        $input = Yii::$app->security->hashData($input, static::getKey());
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }
}
