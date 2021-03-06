<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\helpers\Job;

/**
 * Contact is the model behind the contact form.
 */
class Contact extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Job::sendEmail([
                'from' => [$this->email => $this->name],
                'to' => $email,
                'subject' => $this->subject,
                'textBody' => $this->body,
        ]);
    }
}
