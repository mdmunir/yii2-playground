<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Company */
/* @var $contact app\models\ar\CompanyContact */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="company-form">

    <?php
    $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => '{label} <div class="col-sm-8">{input}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label']
            ]
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?php foreach ($contacts as $i => $contact): ?>
        <?= Html::activeHiddenInput($contact, "[$i]type") ?>
        <?= $form->field($contact, "[$i]value")->textInput()->label($contact->type) ?>
    <?php endforeach; ?>


    <button class="btn btn-primary btn-fab demo-switcher-fab" data-toggle="tooltip" data-placement="top"
            title="Click for Save"><i class="material-icons">save</i></button>


    <?php ActiveForm::end(); ?>

</div>
