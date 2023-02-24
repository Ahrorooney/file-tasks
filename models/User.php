<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken // todo make this field will be changed each time user login
 * @property mixed $token_expire_datetime
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    public string|null $raw_password = Null;
    public static function tableName():string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required' ],
            [['username'], 'string' ],
            ['raw_password', 'string', 'min' => 8],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],

        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getTokenExpireDatetime()
    {
        return $this->token_expire_datetime;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function generateAccessToken()
    {
        $this->accessToken = Yii::$app->security->generateRandomString();
        $this->token_expire_datetime = date("Y-m-d H:i:s", strtotime("+1 hours"));

    }
    public function checkExpirationOfToken()
    {
        $expire_date = Yii::$app->user->getIdentity()->getTokenExpireDatetime();
        $now = date("Y-m-d H:i:s");
        if ($now > $expire_date) {
            throw new NotFoundHttpException("Access Token is expired! Please re-login to get new Access Token.");
        } else {
            return True;
        }
    }
}
