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
            'email' => 'mem1@outlook.com',
            'password' => bcrypt('member1')
        ]);
    }
}
