### テスト用のサーバー起動 ###
cd /home/mikuras/002_study/006.laravel/laravelapp
cd /home/mikuras/003_夢の宅配便/dreamcourier
php artisan serve

### laravel コマンド ###
#  プロジェクトの作成
laravel new laravelapp
laravel new プロジェクト名

#  artisan コマンド
#  コントローラーの作成
php artisan make:controller HelloController
php artisan make:controller 〜
php artisan make:controller AddressMastersController
php artisan make:controller OperatorMenu/ProductRegisterController
php artisan make:controller OperatorMenu/ProductReferenceController
php artisan make:controller OperatorMenu/ProductApprovalController

#  リソースコントローラーの作成（CRUD用）
php artisan make:controller 〜 --resource
php artisan make:controller RestappController --resource

#  サービスプロバイダーの作成
php artisan make:provider 〜
php artisan make:provider OperatorServiceProvider
#  その他コントローラーコマンド
php artisan make:controller 〜 --resource 略(-r)
php artisan make:controller 〜 --model　略(-m)
php artisan make:controller 〜 --invokable　略(-i)  #シングルアクション限定(たぶん)
php artisan make:controller 〜 メンバー１ServiceProvider
php artisan make:controller 〜 --api    #api向け

#  ミドルウェアの作成
#    ※グローバルミドルウェアとして使用したい場合、App/Http/Kernel.phpの$middlewareへの登録も必要
#    ※ルートミドルウェアとして使用したい場合、App/Http/Kernel.phpの$routeMiddlewareへの登録も必要
php artisan make:middleware HelloMiddleware
php artisan make:middleware 〜
php artisan make:middleware RequestConvertMiddleware
php artisan make:middleware MembersConvertMiddleware
php artisan make:middleware ProductRegisterConvertMiddleware
php artisan make:middleware OperatorCodeAddMiddleware

#  バリデーション（フォームリクエスト）の作成
php artisan make:request HelloRequest
php artisan make:request 〜
php artisan make:request MemberRegisterCheckRequest
php artisan make:request MemberRegisterRequest
php artisan make:request ProductRegisterCheckRequest
php artisan make:request ProductRegisterRequest
php artisan make:request ProductSearchRequest
php artisan make:request ProductApprovalRequest
リソース cretate_address_masters_table

#  ルールの作成（カスタムバリデーション）
php artisan make:rule 〜
php artisan make:rule SalesPeriodDuplicationRule

#  マイグレーションファイル作成（テーブル名は複数形が理想）
php artisan make:migration create_※テーブル名_table
php artisan make:migration create_people2_table
php artisan make:migration create_boards_table
php artisan make:migration cretate_restdata_table

php artisan make:migration cretate_member_masters_table
php artisan make:migration cretate_address_masters_table
php artisan make:migration create_product_masters_table

#  セッション用マイグレーションファイル作成
php artisan session:table

#  テーブル変更のマイグレーション(前提：composer require doctrine/dbal　のインストールが必要)
php artisan make:migration add_column_〜_table --table=members
php artisan make:migration change_〜_table --table members
php artisan make:migration change_address_table --table members
php artisan make:migration change_column_zip_table --table=address_masters
php artisan make:migration change_column_product_image --table=product_masters
php artisan make:migration change_column_product_thumbnail --table=product_masters
php artisan make:migration change_column_sales_period_to --table=product_masters


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
php artisan make:seeder OperatorsTableSeeder

#  シーディングを実行
php artisan db:seed
#  シーディングを行いたいクラスを指定したい場合
php artisan db:seed --class=※クラス名
php artisan db:seed --class=RestdataTableSeeder
php artisan db:seed --class=OperatorsTableSeeder

#  モデルの作成(単数形の名前が理想)
php artisan make:model Person
php artisan make:model Board
php artisan make:model AddressMaster
php artisan make:model ProductMasters

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
php artisan make:test MembersTest
php artisan make:test MemberRegisterCheckTest
php artisan make:test MemberRegisterTest
# テスト用スクリプト作成 ./tests/unitの直下に作成される
php artisan make:test 〜Test --unit
# テストの実行：全件 （カレントdir：プロジェクトtop）
vendor/bin/phpunit
# テストの実行：ファイル指定
vendor/bin/phpunit tests/Feature/MemberRegisterTest.php

### 便利コマンド
# artisanコマンドのリストが見れる。
php artisan list
# ルートに関するリストが見れる。
php artisan route:list
# イベントの一覧が見れる。
php artisan event:list

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