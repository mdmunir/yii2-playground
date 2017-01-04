<?php
Yii::setAlias('@rest', dirname(dirname(__DIR__)) . '/rest');
Yii::setAlias('@task', dirname(dirname(__DIR__)) . '/task');
Yii::$classMap['yii\helpers\Url'] = dirname(__DIR__) . '/helpers/Url.php';
