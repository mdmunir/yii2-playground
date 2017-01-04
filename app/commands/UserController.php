<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\models\ar\User;
use app\models\ar\UserProfile;

/**
 * Description of DbSeedController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UserController extends Controller
{

    public function actionInit()
    {
        if (!Console::confirm('Are you sure you want to create some user', true)) {
            return self::EXIT_CODE_NORMAL;
        }
        // users
        $rows = is_file(__DIR__ . '/data/user.php') ? require __DIR__ . '/data/user.php' : [];
        $total = count($rows);
        $created = [];
        if ($total) {
            echo "\ninsert table 'user'";
            Console::startProgress(0, $total);
            foreach ($rows as $i => $row) {
                $row = $this->assoc($row, ['username', 'fullname', 'email', 'password']);
                $user = new User([
                    'scenario' => User::SCENARIO_INIT_CMD,
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'status' => User::STATUS_ACTIVE,
                ]);
                $user->setPassword($row['password']);
                $user->generateAuthKey();

                if ($user->save()) {
                    $created[] = $row['username'];
                    $profile = new UserProfile(['fullname' => $row['fullname']]);
                    $user->link('profile', $profile);
                }
                Console::updateProgress($i + 1, $total);
            }
            Console::endProgress();
        }
        if (count($created)) {
            echo "user was created are \"" . implode('", "', $created) . "\"\n";
        }
    }

    protected function assoc($row, $columns)
    {
        $result = [];
        foreach ($columns as $i => $column) {
            $result[$column] = $row[$i];
        }
        return $result;
    }

    public function actionInitRole()
    {
        if (!Console::confirm('Are you sure you want to create some role', true)) {
            return self::EXIT_CODE_NORMAL;
        }
        // roles
        $roles = [
            ['SuperAdmin', 'Super Administrator'],
            ['Admin', 'Administrator'],
        ];
        $authManager = Yii::$app->authManager;
        foreach ($roles as $row) {
            list($name, $description) = $row;
            if ($authManager->getRole($name) === null) {
                $role = $authManager->createRole($name);
                $role->description = $description;

                $authManager->add($role);
                echo "Create role \"{$name}\"\n";
            }
        }
    }

    public function actionAddRole($name, $description = '')
    {
        $authManager = Yii::$app->authManager;
        if ($authManager->getRole($name) === null) {
            $role = $authManager->createRole($name);
            $role->description = $description;

            $authManager->add($role);
        }
    }

    public function actionGrant(array $users, array $roles)
    {
        $authManager = Yii::$app->authManager;
        foreach ($roles as $i => $name) {
            $role = $authManager->getRole($name);
            if ($role) {
                $roles[$i] = $role;
            } else {
                unset($roles[$i]);
            }
        }
        foreach ($users as $username) {
            $user = User::findByUsername($username);
            if ($user === null) {
                echo "Unknown user \"{$username}\"\n";
                continue;
            }
            $userId = $user->id;
            foreach ($roles as $role) {
                if (!$authManager->checkAccess($userId, $role->name)) {
                    $authManager->assign($role, $userId);
                    echo "Assign role \"{$role->name}\" to user \"{$username}\"\n";
                }
            }
        }
    }
}
