<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ar\search\Company */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Travel Agent';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => false],
        'viewParams' => ['lastIndex' => $dataProvider->getCount() - 1],
        'itemView' => '_item'
    ])
    ?>

</div>
