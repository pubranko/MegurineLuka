<?php

use Illuminate\Database\Seeder;

class ProductMastersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_masters')->insert([
            'product_code'=>'akagi-101',
            'sales_period_from'=>'2020-01-01 10:00:00',
            'sales_period_to'=>'2100-12-31 10:00:00',
            'product_name'=>'アカギと麻雀対決１',
            'product_description'=>'「夢の中でアカギと対戦してみる」シリーズ第一弾！あなたは果たして勝つことができるか？',
            'product_price'=>'1000',
            'product_image'=>'public/product_image/ComingSoon.jpg',
            'product_thumbnail'=>'public/product_thumbnail/ComingSoon.jpg',
            'product_search_keyword'=>'アカギ akagi あかぎ',
            'product_tag'=>'アカギ　ギャンブル　男性向け',
            #'product_stock_quantity'=>'100',
            'status'=>'正式',
            'selling_discontinued_classification'=>'販売可',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_masters')->insert([
            'product_code'=>'akagi-102',
            'sales_period_from'=>'2020-01-01 10:00:00',
            'sales_period_to'=>'2100-12-31 10:00:00',
            'product_name'=>'アカギと麻雀対決２',
            'product_description'=>'「夢の中でアカギと対戦してみる（イージーモード）」シリーズ第二弾！なかなか勝つことができない方向けに、３万点プラスからスタート。さあ、これなら勝てるかも？',
            'product_price'=>'1500',
            'product_image'=>'public/product_image/ComingSoon.jpg',
            'product_thumbnail'=>'public/product_thumbnail/ComingSoon.jpg',
            'product_search_keyword'=>'アカギ akagi あかぎ　赤木',
            'product_tag'=>'アカギ　ギャンブル　男性向け　キャンペーン',
            #'product_stock_quantity'=>'100',
            'status'=>'正式',
            'selling_discontinued_classification'=>'販売可',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        #-----
        DB::table('product_masters')->insert([
            'product_code'=>'washizu-101',
            'sales_period_from'=>'2020-01-01 10:00:00',
            'sales_period_to'=>'2100-12-31 10:00:00',
            'product_name'=>'ワシズと血抜き麻雀対決１',
            'product_description'=>'「夢の中でワシズと血抜き麻雀勝負やってみる」第一弾が登場！'.
                                    'まずは、通常のワシズ麻雀ルールで対戦してみよう。'.
                                    '半荘6回、持ち点20万点、レートは1000点＝10ccの血液（満貫＝80cc）。'.
                                    'さあ、命がけのプレッシャーの中で、あなたは勝つことができるか？',
            'product_price'=>'1200',
            'product_image'=>'public/product_image/ComingSoon.jpg',
            'product_thumbnail'=>'public/product_thumbnail/ComingSoon.jpg',
            'product_search_keyword'=>'ワシズ　washizu わしず　鷲巣',
            'product_tag'=>'ワシズ　ギャンブル　男性向け',
            #'product_stock_quantity'=>'100',
            'status'=>'正式',
            'selling_discontinued_classification'=>'販売可',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('product_masters')->insert([
            'product_code'=>'washizu-102',
            'sales_period_from'=>'2020-01-01 10:00:00',
            'sales_period_to'=>'2100-12-31 10:00:00',
            'product_name'=>'ワシズと血抜き麻雀対決２',
            'product_description'=>'「夢の中でワシズと血抜き麻雀勝負やってみる（ウルトラハードモード）」第二弾が登場！'.
                                    'さあ、原作どおりのルールで勝負！'.
                                    '半荘6回、持ち点2万点、レートは1000点＝100ccの血液（満貫＝800cc）。'.
                                    '満貫(8000点)、跳満(12000点)振ったら即死！'.
                                    '勝つことができたら、もはや人間じゃない？',
            'product_price'=>'1200',
            'product_image'=>'public/product_image/ComingSoon.jpg',
            'product_thumbnail'=>'public/product_thumbnail/ComingSoon.jpg',
            'product_search_keyword'=>'ワシズ　washizu わしず　鷲巣',
            'product_tag'=>'ワシズ　ギャンブル　男性向け　キャンペーン',
            #'product_stock_quantity'=>'100',
            'status'=>'正式',
            'selling_discontinued_classification'=>'販売可',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        #------
        DB::table('product_masters')->insert([
            'product_code'=>'reijyo-tashinami-101',
            'sales_period_from'=>'2020-01-01 10:00:00',
            'sales_period_to'=>'2100-12-31 10:00:00',
            'product_name'=>'公爵令嬢の嗜み',
            'product_description'=>'異世界転生シリーズが登場！'.
                                    'さあ、プレイしてた乙女ゲーの悪役ヒロインの、まさにバッドエンドイベント真っ最中に！？'.
                                    'あなたはここから逆転できるか？',
            'product_price'=>'1200',
            'product_image'=>'public/product_image/ComingSoon.jpg',
            'product_thumbnail'=>'public/product_thumbnail/ComingSoon.jpg',
            'product_search_keyword'=>'公爵　令嬢　嗜み',
            'product_tag'=>'公爵令嬢の嗜み　異世界転生　女性向け　キャンペーン',
            #'product_stock_quantity'=>'100',
            'status'=>'正式',
            'selling_discontinued_classification'=>'販売可',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
    }
}
