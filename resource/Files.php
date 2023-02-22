<?php
namespace app\resource;

class Files extends \app\models\Files
{
    public function fields()
    {
        return [
            'id',
            'filename',
            'extension',
            'user_id',
            'file_location',
            'downloadLink' // only when used locally
        ];
    }
}