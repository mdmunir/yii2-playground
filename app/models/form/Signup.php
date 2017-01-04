<?php

namespace app\models\form;

use Yii;
use app\models\ar\User;
use app\models\ar\UserProfile;
use yii\base\Model;
use yii\helpers\Url;
use app\helpers\Job;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $fullname;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname'], 'required'],
            [['fullname'], 'string', 'min' => 3, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['username', 'default', 'value' => function() {
                    return User::getUniqueUsername(explode('@', $this->email)[0]);
                }],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'checkUsername', 'clientValidate' => 'checkUsernameClient'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['email', 'unique', 'targetClass' => '\app\models\ar\User'],
            ['username', 'unique', 'targetClass' => '\app\models\ar\User'],
        ];
    }

    public function checkUsername()
    {
        if (in_array(strtolower($this->username), ['admin', 'administrator', 'superadmin', 'super', 'root'])) {
            $this->addError('username', 'Username is invalid.');
        }
    }

    public function checkUsernameClient()
    {
        $options = json_encode([
            'range' => ['admin', 'administrator', 'superadmin', 'super', 'root'],
            'not' => true,
            'message' => 'Username is invalid.',
            'skipOnEmpty' => 1
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return "yii.validation.range(value.toLowerCase(), messages, $options);";
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $transaction = Yii::$app->db->beginTransaction();

            if ($user->save()) {
                $profile = new UserProfile([
                    'fullname' => $this->fullname,
                ]);
                $user->link('profile', $profile);
                $this->sendEmail($user);
                //Yii::$app->session->setFlash('success', 'Registration success. Check your email');
                $transaction->commit();
                return $user;
            }
            $transaction->rollBack();
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function sendEmail($user)
    {
        /* @var $mailer \yii\mail\BaseMailer */
        /* @var $message \yii\mail\BaseMessage */
        Url::$app = 'web';
        $token = Yii::$app->tokenManager->generateToken($user->id, 'activate.account');
        $params = [
            'user' => $user,
            'activateLink' => Url::to(['/user/activate', 'token' => $token, 'action' => 'a'], true),
            'rejectLink' => Url::to(['/user/activate', 'token' => $token, 'action' => 'r'], true),
        ];

        return Job::sendEmail([
                'view' => ['html' => 'activationAccount-html', 'text' => 'activationAccount-text'],
                'params' => $params,
                'from' => Yii::$app->params['supportEmail'],
                'to' => $this->email,
                'subject' => 'Activation account for piknikio',
        ]);
    }
}
