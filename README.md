## ダウンロード方法

git clone
git clone https://github.com/fs1204/laravel_ec.git

git clone ブランチを指定してダウンロードする場合
git clone -b ブランチ名 https://github.com/fs1204/laravel_ec.git

もしくは、zipファイルでダウンロードください。

## インストール方法
-cd laravel_ec
-composer install
-npm install
-npm run dev

.env.example をコピーして、.envファイルを作成

.envファイルの中の下記をご利用の環境に合わせて変更してください。

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_ec
DB_USERNAME=sail
DB_PASSWORD=password

XAMPP/MAMP または 他の開発環境でDBを起動した後に

php artisan migrate:fresh --seed

と実行してください。（データベーステーブルとダミーデータが追加されればOK）

最後に
php artisan key:generate
と入力してキーを生成後、

<!-- APP_KEY=base64:46+Hd3So6nThtYGCt7jsd0st/7dfIpZ4ImnBAk4yY2E=
    # laravelをcomposerでインストールしたときは自動で生成されるが、
    # githubでインストールすると、.envファイル自体がないので、キーもなくて、キーがないと動かないので、キーを生成する必要がある。 -->

php artisan serve
で簡易サーバーを立ち上げ、表示確認してください。

## インストール後の実施事項

画像のダミーデータは、public/imagesフォルダ内に、sample1.jpg~sample6.jpg として保存しています。

php artisan storage:link で storageフォルダにリンク後、
storage/app/public/productsフォルダ内に保存すると表示されます。
（productsフォルダがない場合は、作成してください。）

ショップの画像も表示する場合は、
storage/app/public/shopsフォルダを作成し、
画像を保存してください。
