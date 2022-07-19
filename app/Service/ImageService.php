<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService {

    // 商品画像の保存先はpublic/products/にしたいので、引数にフォルダ名を持ってくる
    public static function upload($imageFile, $folderName) {

        // dd($imageFile);
        //shopで1つのファイルをアップロードするとき
        // ^ Illuminate\Http\UploadedFile {#1371 ▼ // 1つのファイル
        //     ...
        //   }
        //

        // image で複数ファイルをアップロード
        // array:1 [▼  // 'image'をキーに持つ配列
        //      "image" => Illuminate\Http\UploadedFile {#1372 ▼
        //        ...
        //      }
        // ]

        // dd($imageFile['image']);
        // ^ Illuminate\Http\UploadedFile {#1372 ▼  UploadedFileオブジェクト となる
        //     ...
        //   }

        if(is_array($imageFile)) {
            $file = $imageFile['image']; // 配列なので[ʻkeyʼ] で取得
            // $imageFileが配列なら['image']をつけるとファイルを取得できる。
        } else {
            $file = $imageFile;
        }

        // $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

        $fileName = uniqid(rand().'_');
        // $extension = $imageFile->extension();
        $extension = $file->extension();
        $fileNameToStore = $fileName. '.' . $extension;

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);

        return $fileNameToStore;    // ショップ情報を保存する際に作成したファイル名が必要となる。
    }

}


