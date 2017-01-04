<?php

namespace app\commands;

/**
 * Description of CmdController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class CmdController extends \dee\queue\WorkerController
{

    public function actionIndex()
    {
        $params = $this->getActionParams();
        $params['args'] = func_get_args();
        $file = __DIR__ . "/cmds/{$params['name']}.php";
        if (is_file($file)) {
            return $this->runCommand($file, $params);
        }
        echo date('Y-m-d H:i:s '), $params['name'], "\n";
    }

    protected function runCommand($_file_, $_params_)
    {
        extract($_params_);
        return require $_file_;
    }
}
