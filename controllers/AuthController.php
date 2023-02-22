<?php

namespace app\controllers;

use app\models\LoginFormApi;
use Yii;
use yii\rest\ActiveController;

class AuthController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionLogin(): LoginFormApi|bool|array
    {
        $model = new LoginFormApi();
        $data = Yii::$app->request->post();
        $model->username = $data['username'];
        $model->password = $data['password'];
        return $model->login();
    }
}