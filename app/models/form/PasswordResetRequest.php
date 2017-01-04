<?php

namespace app\models\form;

use Yii;
use app\models\ar\User;
use yii\base\Model;
use yii\helpers\Url;
use app\helpers\Job;

/**
 * Password reset request form
 */
class PasswordResetRequest extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\models\ar\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
        ]);

        if ($user) {
            $token = Yii::$app->tokenManager->generateToken($user->id, 'reset.password', Yii::$app->params['user.passwordResetTokenExpire']);

            return Job::sendEmail([
                    'view' => ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    'params' => ['user' => $user, 'resetLink' => Url::to(['/user/reset-password', 'token' => $token], true)],
                    'from' => Yii::$app->params['supportEmail'],
                    'to' => $this->email,
                    'subject' => 'Password reset for piknikio',
            ]);
        }

        return false;
    }
}
