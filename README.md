#  勤怠管理システム
**概要説明**<br>
・企業の勤怠管理システムになります。
勤怠管理システムとは、出退勤時間の打刻から休憩時間の打刻まで行え、日付ごとに全従業員の勤怠管理から個別ユーザーの勤怠管理に関するデータまで確認できるシステムになっています。

## 作成目的
・人事評価の為

## アプリケーションURL
- 会員登録ページ: /register
- ログインページ: /login
- 打刻ページ: /
- 日付別勤怠ページ: /attendance
- ユーザーページ: /mypage/user

## 他のリポジトリ
- git clone リンク: git@github.com:asuen39/stamping-application-laravel.git</a>
- aws ec2: http://18.183.173.15/

## 機能一覧
- 会員登録機能<br>
・登録ユーザーへのメール送信機能
- ログイン機能
- ログアウト機能
- 勤務開始機能
- 勤務終了機能
- 休憩開始機能
- 休憩終了機能
- 日付別勤怠情報取得機能
- ページネーション機能
- ユーザーページ<br>
・会員ユーザー取得機能<br>
・会員ユーザー勤怠情報取得機能
- ログイン状態取得機能 ※取得されない時にログインへリダイレクトさせる。

## 使用技術
- PHP 8.0
- Laravel  8.83
- Mysql 8.0
- bootstrap(CSSのみ) 5.3.0
- Mailhog
- AWS S3、EC2、RDS

## テーブル設計
![table_specifications](https://github.com/asuen39/stamping-application-laravel/assets/68514566/23f33d27-8b82-4498-953c-7e42f699f200)


## ER図



## 環境構築
- git cloneをする。
- docker-compose up -d --build
- ※Mysqlは環境によって起動しない場合があります。それぞれの環境に合わせてdocker-compose.ymlの編集を行ってください。
- ※osによってファイルの権限の指定する可能性があります。sudo chmod -R 777 * 等環境に合わせて指定してください。
- ※docker-compose絡みで解消出来ないエラーが発生した時 以下実行で解決出来ます。
- コンテナの停止
- コンテナの削除 (docker/mysql/dataディレクトリの削除)
- PC再起動後にdocker-compose build --no-cache
- docker-compose up -d

## laravel環境構築
- docker-compose exec php bash
- composer install
- .env.exampleからコピーして.env ファイル作成。環境変数を設定。データベースが作成されているか確認。
- php artisan key:generate
- php artisan migrate -テーブル作成
- php artisan db:seed　テーブルへデータの挿入

## 備考
- Mailhog: http://localhost:8025/
- aws ※S3、EC2、RDSの機能だけ用意しました。<br>

