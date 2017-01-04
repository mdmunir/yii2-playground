<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ar\Company */
$this->registerCss(<<<CSS
input.jcrop-keymgr {
    display: none;
}
CSS
);

app\assets\CropAsset::register($this);
$this->registerJsFile('@web/js/_jcropbox.js',[
    'depends' => 'app\assets\PaperAdmin'
]);
//$this->registerJs($this->render('_jcropbox.js'));

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Travel Agent', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$isEditable = $model->user_id == Yii::$app->user->id;
$isAdmin = Yii::$app->user->can('Admin');
?>
<div data-widget-group="group1">
    <div class="row">
        <!--PHOTO BOX|INFO-->
        <div class="col-md-3">
            <div class="panel panel-profile">
                <div class="panel-body">
                    <div class="row">
                        <a data-toggle="modal" data-target="#ChangePhotoDlg" id="btn-change-photo">
                            <?=
                            Html::img($model->photoUrl ?: '@web/img/no_image_avaliable.jpg', [
                                'class' => 'media-object img-responsive img-thumbnail',
                                'width' => '200', 'height' => '400'])
                            ?>
                        </a>
                    </div>
                    <?php if($isAdmin): ?>
                        <a href="<?= Url::to(['verify','id'=>$model->id])?>" class="btn btn-danger" >Verify</a>
                        <a href="<?= Url::to(['black-list','id'=>$model->id])?>" class="btn btn-danger" >Black List</a>
                    <?php endif;?>
                </div>
            </div>

            <div class="panel panel-profile">
                <div class="panel-body">
                    <div>
                        <div class="personel-info ">
                            <span class="icon" title="Name"><i class="material-icons">person</i></span>
                            <span class="tooltips" data-trigger="hover" data-original-title="<?= Html::encode($model->name) ?>">
                                <?= Html::encode(StringHelper::truncate($model->name, 25, '...')) ?>
                            </span>
                        </div>
                        <div class="personel-info">
                            <span class="icon" title="Address"><i class="material-icons">place</i></span>
                            <?php if ($model->address) : ?>
                                <span class="tooltips" data-trigger="hover" data-original-title="<?= Html::encode($model->address) ?>">
                                    <?= Html::encode(StringHelper::truncate($model->address, 25, '...')) ?>
                                </span>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </div>
                        <div class="personel-info">
                            <span class="icon" title="Phone"><i class="material-icons">call</i></span>
                            <span>

                            </span>
                        </div>

                    </div>

                </div>
            </div><!-- panel -->
        </div><!-- col-sm-3 -->

        <div class="col-md-9">
            <div class="tab-content">
                <div class="panel-profile">
                    <div class="tab-content">

                        <!--start: TAB ABOUT-->
                        <div class="tab-pane p-md active" id="about">
                            <div class="about-area">
                                <?php if ($isEditable): ?>
                                    <span class="badge badge-primary pull-right">
                                        <a href="<?= Url::to(['update']) ?>" style="color: #fff">
                                            <i class="fa fa-edit"></i> edit
                                        </a>
                                    </span>
                                <?php endif; ?>
                                <h4>
                                    About <?= $model->name ?>
                                </h4>
                                <?= $model->description ?>
                            </div>

                            <div class="about-area">
                                <!--<h4>Personal Information</h4>-->
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td><?= Html::encode($model->name) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td><?= Html::encode($model->address) ?></td>
                                            </tr>
                                            <?php foreach ($model->contacts as $contact): ?>
                                            <tr>
                                                <th><?= Html::encode($contact->type)?></th>
                                                <td><?= $contact->nValue ?></td>
                                            </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!--end: TAB ABOUT-->

                    </div>
                </div>
            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>



<?php if ($isEditable): ?>
    <div id="ChangePhotoDlg" class="modal fade in" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <?=
            Html::beginForm(['upload-photo', 'id' => $model->id], 'post', [
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data'
            ]);
            ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h2 class="modal-title">Change photo</h2>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" id="img-container" style="min-height: 300px;text-align: center; align-content: center">
                        <img class="img-responsive"  id="img-content" style="max-height: 350px;">
                    </div>
                    <input type="hidden" name="crop[x]" id="cx">
                    <input type="hidden" name="crop[y]" id="cy">
                    <input type="hidden" name="crop[w]" id="cw">
                    <input type="hidden" name="crop[h]" id="ch">
                    <input type="hidden" name="crop[er]" id="er">
                    <input type="file" id="inp-image" name="image" style="visibility:hidden;" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="select-file">Select File</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-raised btn-primary" id="btn-submit" style="display: none;">
                        Upload<div class="ripple-container"></div></button>
                </div>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
    <?php $this->registerJsFile('@web/paperadmin/plugins/form-jasnyupload/fileinput.min.js', ['depends' => 'app\assets\PaperAdmin']); ?>
<?php endif; ?>
