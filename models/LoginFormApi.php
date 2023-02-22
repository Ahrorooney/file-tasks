<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class LoginFormApi extends Model
{
    public $username;
    public $password;
//    public mixed $user;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword(string $attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function login(): LoginFormApi|bool|array
    {
        //validate loaded data
        if ($this->validate()) {
            //find user with username and password
            $user = $this->getUser();

            if (Yii::$app->user->login($user)) {
                $user->generateAccessToken();
                $user->save();
                $a = ArrayHelper::toArray($user, [
                    'app\models\User' => [
                        'id',
                        'username',
                        'accessToken',
                    ],
                ]);
                return $a;
            }
        } else {
            throw new NotFoundHttpException($this->getErrorSummary(false)[0]);
        }
        return true;
    }

    public function getUser(): User|bool|null
    {
        $user = User::findOne([
            'username' => $this->username,
        ]);

        if (empty($user)) {
            return false;
        }

        return $user;
    }
}