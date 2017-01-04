<?php

namespace app\widgets;

/**
 * Description of CropAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class CropAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/widgets/assets';
    public $js = [
        'http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.js',
        'js/dcropbox.js',
    ];
    public $css = [
        'http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
