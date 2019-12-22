### テスト用のサーバー起動 ###
cd /home/mikuras/002_study/006.laravel/laravelapp
php artisan serve

### laravel コマンド ###
#  プロジェクトの作成
laravel new laravelapp
laravel new プロジェクト名

#  artisan コマンド
#  コントローラーの作成
php artisan make:controller HelloController
php artisan make:controller 〜
#  リソースコントローラーの作成
php artisan make:controller 〜 --resource
php artisan make:controller RestappController --resource
#  その他コントローラーコマンド
php artisan make:controller 〜 --resource(-r)
php artisan make:controller 〜 --model(-m)
php artisan make:controller 〜 --invokable(-i)
php artisan make:controller 〜 --parent(-p)

#  サービスプロバイダの作成
php artisan make:provider HelloServiceProvider
php artisan make:provider 〜

#  ミドルウェアの作成
php artisan make:middleware HelloMiddleware
php artisan make:middleware 〜

#  バリデーション（フォームリクエスト）の作成
php artisan make:request HelloRequest
php artisan make:request 〜

#  マイグレーションファイル作成（テーブル名は複数形が理想）
php artisan make:migration create_※テーブル名_table
php artisan make:migration create_people2_table
php artisan make:migration create_boards_table
php artisan make:migration cretate_restdata_table

php artisan make:migration cretate_member_masters_table
php artisan make:migration cretate_address_masters_table

#セッション用マイグレーションファイル作成
php artisan session:table

#  マイグレーション実行(上記のマイグレーションファイルにカラムの記述後、以下のコマンドで実際のテーブルが作成される)
php artisan migrate

#　マイグレーションをロールバック
php artisan migrate:rollback
php artisan migrate:rollback --step=4

#  マイグレーション（リフレッシュ：全て最初に戻してからマイグレーションやり直し）
php artisan migrate:refresh
php artisan migrate:refresh --step=5    #巻き戻す場所を指定もできる
php artisan migrate:refresh --seed      #シードの実行も同時にできる。

#  シーダーファイルの作成
php artisan make:seeder ※テーブル名TableSeeder
php artisan make:seeder People2TableSeeder

#  シーディングを実行
php artisan db:seed
#  シーディングを行いたいクラスを指定したい場合
php artisan db:seed --class=※クラス名
php artisan db:seed --class=RestdataTableSeeder

#  モデルの作成(単数形の名前が理想)
php artisan make:model Person
php artisan make:model Board

### ペジネーションのテンプレートの用意
# これで「/resources/views/vendor/pagination」にテンプレートがコピーされるようだ
php artisan vendor:publish --tag=laravel-pagination

### ユーザー認証
# ユーザー認証用のマイグレーションファイル作成
# php artisan make:auth これは古いコマンド。laravel6から方法変更
# プロジェクトTOPで必要なものをインストール(これはプロジェクトごとに行う)
composer require laravel/ui
php artisan ui vue --auth       #vueで実装の場合
#php artisan ui react --auth  reactで実装の場合
# これでHomeControllerとマイグレーションファイルが自動で作成されている
# あと初回はこれのインストールが必要。　sudo apt install npm
# 次に以下のコマンドを実行し、必要なものをインストール。(これもcomposerと同じくプロジェクトごと？？？)
npm install && npm run dev

### Unit Test
# テスト用スクリプト作成 ./tests/featureの直下に作成される
php artisan make:test Person2Test
# テストの実行（カレントdir：プロジェクトtop）
vendor/bin/phpunit

#  テーブル作成
create table testDB.people(
    id mediumint unsigned not null auto_increment,
    name varchar(50),
    mail varchar(50),
    age TINYINT UNSIGNED,
    primary key (id)
    );

insert into testDB.people (name,mail,age) values('なまえ','a5@ex.com',5);
insert into testDB.people (name,mail,age) values('なまえ2','a10@ex.com',10);