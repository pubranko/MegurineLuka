<?php

use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('members')->insert([
            #'name' => 'メンバー１',
            'member_code' => '00007',
            'email' => 'mem1@ex.com',
            'password' => bcrypt('member1'),
            'last_name' => '名１',
            'first_name' => '姓１',
            'last_name_kana' => 'ナイチ',
            'first_name_kana' => 'セイイチ',
            'birthday' => '2000/12/24',
            'sex' => '男',
            'postal_code1' => '340',
            'postal_code2' => '0035',
            'address1' => '埼玉県',
            'address2' => '草加市',
            'address3' => '西町',
            'address4' => '７６５−５',
            'address5' => 'セフィラ西',
            'address6' => '２０３号室',
            'phone_number1' => '090',
            'phone_number2' => '9394',
            'phone_number3' => '4781',
            'enrollment_datetime' => '2019/12/22 9:43:59', #mktime(9,43,59,12,22,2019),
            #'unsubscribe_reason' => '',
            'status' => '正式',
            #'purchase_stop_division' => '',
            #'temporary_update_operator_code' => '',
            #'temporary_update_approval_operator_code' => '',
            #'remember_token' => '',
            'created_at' => '2019/12/22 9:43:59',
            'updated_at' => '2019/12/23 10:51:20',
        ]);
    }
}
