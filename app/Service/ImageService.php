<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService {

    // 商品画像の保存先はpublic/products/にしたいので、引数にフォルダ名を持ってくる
    public static function upload($imageFile, $folderName) {

        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        $fileName = uniqid(rand().'_');
        $extension = $imageFile->extension();
        $fileNameToStore = $fileName. '.' . $extension;

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);

        return $fileNameToStore;    // ショップ情報を保存する際に作成したファイル名が必要となる。
    }

}


