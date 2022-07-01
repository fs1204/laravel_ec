<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceContainerTest()
    {
        app()->bind('lifeCycleTest', function() {
            return 'ライフサイクルテスト';
        });

        $test = app()->make('lifeCycleTest');

        // サービスコンテナを使わないパターン   それぞれのクラスを1度インスタンス化する
        $message = new Message();
        $sample = new Sample($message);
        $sample->run(); // メッセージ表示

        // サービスコンテナ(app())を使うパターン
        // newでインスタンス化しなくても使える Sampleクラスの内部でメッセージクラスも設定する必要があったが、
        // 自動的に依存関係を解決してapp()のmakeだけで使えるようになっている
        app()->bind('sample', Sample::class); // クラスを紐づけることができる
        $sample = app()->make('sample');
        $sample->run(); // メッセージ表示

        dd($test, app());
    }
}


class Sample {
    public $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }
    public function run() {
        $this->message->send();
    }
}

class Message {
    public function send() {
        echo 'メッセージ表示';
    }
}
