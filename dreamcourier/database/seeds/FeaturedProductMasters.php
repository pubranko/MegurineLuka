<?php

use Illuminate\Database\Seeder;

class FeaturedProductMasters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('featured_product_masters')->insert([
            'introduction_tag'=>'今注目のキャンペーン商品！！！',
            'priority'=>'10',
            'product_tag'=>'キャンペーン',
            'validity_period_from'=>'2020-01-01 10:00:00',
            'validity_period_to'=>'2100-12-31 10:00:00',
            'status'=>'正式',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('featured_product_masters')->insert([
            'introduction_tag'=>'ギャンブル！',
            'priority'=>'20',
            'product_tag'=>'ギャンブル',
            'validity_period_from'=>'2020-01-01 10:00:00',
            'validity_period_to'=>'2100-12-31 10:00:00',
            'status'=>'正式',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);
        DB::table('featured_product_masters')->insert([
            'introduction_tag'=>'異世界転生シリーズ！',
            'priority'=>'30',
            'product_tag'=>'異世界転生',
            'validity_period_from'=>'2020-01-01 10:00:00',
            'validity_period_to'=>'2100-12-31 10:00:00',
            'status'=>'正式',
            'temporary_updater_operator_code'=>'ope2',
            'temporary_update_approver_operator_code'=>'ope3',
            'created_at'=>date('Y/m/d H:i:s'),
            'updated_at'=>date('Y/m/d H:i:s'),
        ]);

    }
}
