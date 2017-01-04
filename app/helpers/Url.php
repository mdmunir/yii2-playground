<?php

namespace yii\helpers;

use Yii;

/**
 * Description of Url
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Url extends BaseUrl
{
    public static $app;

    protected static function getUrlManager()
    {
        $configs = [
            'web' => [
                'app-rest' => 'webUrlManager',
                //'app-console' => 'urlManager',
            ],
            'api' => [
                'app-web' => 'apiUrlManager',
                'app-console' => 'apiUrlManager',
            ]
        ];

        $appId = Yii::$app->id;
        if (static::$app !== null && isset($configs[static::$app][$appId])) {
            return Yii::$app->get($configs[static::$app][$appId]);
        }
        return parent::getUrlManager();
    }
}
