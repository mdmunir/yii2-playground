<?php

/* @var $this yii\web\View */
/* @var $user common\models\ar\User */

?>
Hello <?= $user->username ?>,

Follow the link below to activate your account:

<?= $activateToken ?>

Or the link bellow to reject:

<?= $rejectLink ?>