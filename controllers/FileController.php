<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\resource\Files;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FileController extends ActiveController
{
    public $modelClass = 'app\resource\Files';
    public String $user_role;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'index', 'view'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        $this->user_role = array_values(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name;

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = Files::find();
        switch ($this->user_role) {
            case 'user':
                $query->andWhere(['user_id' => $this->user->id]);
                break;
            case 'moderator':
//                Moderator can see only his files among 'moderators' and all 'user's files
                $query->leftJoin('auth_assignment', '`auth_assignment`.`user_id` = `files`.`user_id`')
                ->andWhere(['auth_assignment.item_name' => 'user'])
                ->orWhere(['files.user_id' => Yii::$app->user->id]);
                break;
            default:
                break;
        }
        $query->orderBy(['files.id' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionCreate()
    {

        $files = UploadedFile::getInstancesByName('upload_files');

        foreach ($files as $file) {
            $model = new Files();
            $model->upload_file = $file;
            $model->user_id = Yii::$app->user->id;
//            var_dump($file); die;
            if (!$model->save()) {
                return $model;
            }
        }
        return True;
    }

    /**
     * Deletes an existing AgentsLiveLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $file_object = $this->checkUserAccess($id);
        $file_usage_count = Files::find()->andWhere(['hash_sum' => $file_object->hash_sum])->count();
        if ($file_usage_count == 1) {
            unlink(Yii::$app->basePath . '/web' . $file_object->file_location);
        }
        $file_object->delete();

        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $file_object = $this->checkUserAccess($id);
        return $file_object;
    }
    public function actionDownload($id)
    {
        $file_object = $this->checkUserAccess($id);
        $path = Yii::getAlias("@webroot") .$file_object->file_location;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path, $file_object->filename . '.' . $file_object->extension);
        } else {
            throw new NotFoundHttpException("File not found!");
        }
    }

    public function checkUserAccess($id)
    {
        $file_object = Files::findOne($id);
        if (!$file_object) {
            throw new NotFoundHttpException("Not Found!");
        }
        switch ($this->user_role) {
            case 'user':
                if ($file_object->user_id != Yii::$app->user->id)
                {
                    throw new MethodNotAllowedHttpException("You have not permission to delete this object!");
                }
                break;
            case 'moderator':
                $file_author_role = AuthAssignment::findOne(['user_id' => $file_object->user_id])->item_name;
                if ($file_author_role == 'admin') {
                    throw new MethodNotAllowedHttpException("You have not permission to delete this object!");
                } else if ($file_author_role == 'moderator') {
                    if ($file_object->user_id != Yii::$app->user->id)
                    {
                        throw new MethodNotAllowedHttpException("You have not permission to delete this object!");
                    }
                }
                break;
            default:
                break;
        }
        return $file_object;
    }


}