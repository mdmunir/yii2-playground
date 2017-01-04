<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PaperAdmin extends AssetBundle
{
    public $basePath = '@webroot/paperadmin';
    public $baseUrl = '@web/paperadmin';
    public $css = [
        //'js/jqueryui.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        'fonts/glyphicons/css/glyphicons.min.css',
        'css/styles.css',
        'plugins/codeprettifier/prettify.css',
        'plugins/dropdown.js/jquery.dropdown.css',
        'plugins/progress-skylo/skylo.css',
    ];
    public $js = [
        //'js/jqueryui-1.10.3.min.js',
        'js/bootstrap.min.js',
        'js/enquire.min.js',
        'plugins/velocityjs/velocity.min.js',
        'plugins/velocityjs/velocity.ui.min.js',
        'plugins/progress-skylo/skylo.js',
        'plugins/wijets/wijets.js',
        'plugins/sparklines/jquery.sparklines.min.js',
        'plugins/codeprettifier/prettify.js',
        'plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js',
        'plugins/nanoScroller/js/jquery.nanoscroller.min.js',
        'plugins/dropdown.js/jquery.dropdown.js',
        'plugins/bootstrap-material-design/js/material.min.js',
        'plugins/bootstrap-material-design/js/ripples.min.js',
        'js/application.js',
        'demo/demo.js',
        'demo/demo-switcher.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
    ];
}
