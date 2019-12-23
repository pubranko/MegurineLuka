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
            'name' => 'メンバー１',
            'member_code' => '00007',
            'email' => 'mem1@ex.com',
            'password' => bcrypt('member1')
        ]);
    }
}
