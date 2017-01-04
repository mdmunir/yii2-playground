<?php

namespace app\helpers;

use Yii;
use Symfony\Component\Process\Process;
use yii\helpers\Console;
use app\models\ar\TravelPackage;
use yii\db\Query;

/**
 * Description of CodeSynchron
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Synchron
{

    public static function push($commits)
    {
        
    }

    public static function code($force = false)
    {
        $commits = Yii::$app->cache->get('commit-state');
        if (empty($commits)) {
            return true;
        }
        echo "Time execute synchron code :" . date('Y-m-d H:i:s')."\n";
        Yii::$app->cache->set('commit-state', []);

        $composer = $migrate = $force;
        foreach ($commits as $commit) {
            if (!$composer && isset($commit['modified']) && in_array('composer.lock', $commit['modified'])) {
                $composer = true;
            }
            if (!$migrate && isset($commit['added'])) {
                foreach ($commit['added'] as $file) {
                    if (fnmatch('app/migrations/*', $file)) {
                        $migrate = true;
                        break;
                    }
                }
            }
            if ($migrate && $composer) {
                break;
            }
        }

        $cwd = dirname(Yii::getAlias('@app'));
        static::gitUpdate($cwd);
        if ($composer) {
            $process = new Process('composer install --prefer-dist -n', $cwd);
            $process->run();
            if ($process->isSuccessful()) {
                Console::stdout($process->getOutput());
            } else {
                Console::stdout($process->getErrorOutput());
            }
        }
        if ($migrate) {
            $process = new Process('php yii migrate --interactive=0', $cwd);
            $process->run();
            if ($process->isSuccessful()) {
                Console::stdout($process->getOutput());
            } else {
                Console::stdout($process->getErrorOutput());
            }
        }
        return true;
    }

    protected static function gitUpdate($cwd)
    {
        $auth = '';
        if (Yii::$app->params['gitlab.user']) {
            $auth = rawurlencode(Yii::$app->params['gitlab.user']);
            if (Yii::$app->params['gitlab.password']) {
                $auth .= ':' . rawurlencode(Yii::$app->params['gitlab.password']);
            }
            $auth .= '@';
        }
        $url = strtr(Yii::$app->params['gitlab.url'], ['{auth}' => $auth]);
        $process = new Process("git pull --no-tags $url", $cwd);
        $process->run();
        if ($process->isSuccessful()) {
            Console::stdout($process->getOutput());
        } else {
            Console::stdout($process->getErrorOutput());
        }
    }
}
