<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Password reset request form.
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Wrong email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool Whether the email was send.
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(['status' => User::STATUS_ACTIVE, 'email' => $this->email]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            return false;
        }


        $to      = $this->email;
        $subject = 'Password reset for ' . Yii::$app->name;

        $message = Yii::$app->controller->renderPartial('passwordResetToken',[
            'user' => $user
        ]);

        Yii::$app->mailer->compose()
        ->setTo($to)
        ->setFrom([Yii::$app->params['supportEmail'] => 'SNST Account'])
        ->setSubject('[SNST] Reset Password')
        ->setHtmlBody($message)
        ->send();
        return true;
    }
}
