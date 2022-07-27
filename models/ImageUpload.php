<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'image'],
        ];
    }

    public function uploadFile(UploadedFile $file, $curImage)
    {
        $this->image = $file;

        if ($this->validate())
        {
            $this->deleteCurrentImage($curImage);

            return $this->saveImage();
        }
        return '404';
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFileName()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($curImage)
    {
        if ($this->fileExists($curImage)) {
            unlink($this->getFolder() . $curImage);
        }
    }

    private function fileExists($curImage)
    {
        return $curImage && file_exists($this->getFolder() . $curImage);
    }

    private function saveImage()
    {
        $filename = $this->generateFileName();
        $this->image->saveAs($this->getFolder() . $filename);
        return $filename;
    }
}