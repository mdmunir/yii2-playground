<?php

namespace app\assets;

use Yii;

/**
 * Description of GoogleMap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class GoogleMap extends \yii\web\AssetBundle
{

    public function init()
    {
        $params = http_build_query(Yii::$app->params['google.map']);
        $jsFile = 'https://maps.googleapis.com/maps/api/js' . (empty($params) ? '' : '?' . $params);
        $this->js = [$jsFile];
        parent::init();
    }
}
