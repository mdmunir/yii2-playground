<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\helpers\Synchron;

/**
 * Description of CronController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class CronController extends Controller
{

    public function actionSynchronCode()
    {
        Synchron::code();
    }
}
