<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\ar\UserProfile */

$this->title = 'Update Profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<div class="row">
    <div class="col-md-12">
        <!--<div class="panel panel-default">-->
            <?php
            $form = ActiveForm::begin([
                    'id' => 'form-profile',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal tabular-form'
                    ],
                    'fieldConfig' => [
                        'template' => '{label} <div class="col-sm-8">{input}</div>',
                        'labelOptions' => ['class' => 'col-sm-2 control-label']
                    ],
            ]);
            ?>
            <?= $form->field($model, 'fullname') ?>
            <?= $form->field($model, 'bio') ?>
            <?= $form->field($model, 'address') ?>
            <?= $form->field($model, 'gender')->radioList(['male' => 'male', 'female' => 'female']) ?>
            <?= $form->field($model, 'birth_day')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>
            
            
        
            <button class="btn btn-primary btn-fab demo-switcher-fab" data-toggle="tooltip" data-placement="top"
            title="Click for Save">
                <i class="material-icons">save</i>
            </button>
            <?php ActiveForm::end(); ?>
        <!--</div>-->
    </div>
</div>
