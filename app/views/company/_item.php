<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ar\Company;

/* @var $this View */
/* @var $model Company */
$MAXCOL = 4;
$COLWIDTH = (int) 12 / $MAXCOL;
?>
<?= $index % $MAXCOL == 0 ? '<div class="row">' : '' ?>
<div class="col-md-<?= $COLWIDTH ?>">
    <div class="panel panel-white" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
        <a href="<?= Url::to(['view', 'id' => $model->id]) ?>">
            <div class="card-image">
                <div class="item">
                    <?=
                    Html::img($model->photoUrl ? : '@web/img/no_image_avaliable.jpg', [
                        'class' => 'media-object img-responsive img-thumbnail',])
                    ?>
                </div>
            </div>
        </a>
        <div class="p-sm"><h4><?= $model->name ?></h4></div>
        <div class="panel-body ov-h">
            <p>
                <?= $model->description ?>
            </p>
        </div>
    </div>
</div>
<?= $index % $MAXCOL == $MAXCOL - 1 || $index == $lastIndex ? '</div>' : '' ?>
