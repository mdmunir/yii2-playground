<?php

namespace app\assets;

/**
 * Description of CropAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class CropAsset extends \yii\web\AssetBundle
{
    public $js = [
        'http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js'
    ];
    public $css = [
        'http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
