<?php

use Illuminate\Database\Seeder;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('operators')->insert([
            'name' => 'オペレーター１',
            'operator_code' => 'ope1',
            'email' => 'ope1@example.com',
            'password' => bcrypt('operator1')
        ]);
    }
}
