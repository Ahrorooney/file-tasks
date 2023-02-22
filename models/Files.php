<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string|null $hash_sum
 * @property string|null $filename
 * @property string|null $extension
 * @property int $user_id
 * @property string|null $file_location
 *
 * @property User $user
 */
class Files extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['hash_sum', 'filename', 'extension', 'file_location'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['upload_file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash_sum' => 'Hash Sum',
            'filename' => 'Filename',
            'extension' => 'Extension',
            'user_id' => 'User ID',
            'file_location' => 'File Location',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDownloadLink(){
        return Yii::getAlias("@webroot") .$this->file_location;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->hash_sum = (md5_file($this->upload_file->tempName));
        $this->filename = $this->upload_file->getBaseName();
        $this->extension = $this->upload_file->getExtension();
        $file = Files::find()->andWhere(['hash_sum' => $this->hash_sum])->one();
        if ($file) {
//            if file exists, just save the record not file
            $this->file_location = $file->file_location;
        } else {
            $file_path = 'uploads/'.$this->filename. '-' .$this->user_id .'.'. $this->upload_file->extension;
            if (!is_dir(dirname($file_path))){
                FileHelper::createDirectory(dirname($file_path));
            }
            $this->file_location = '/'.$file_path;
            $this->upload_file->saveAs(Yii::getAlias("@web") .$file_path);
        }

        $saved = parent::save($runValidation, $attributeNames);

        if(!$saved) {
            return false;
        }

        return true;
    }

}
