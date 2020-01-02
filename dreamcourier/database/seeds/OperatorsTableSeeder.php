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
        /*
        DB::table('operators')->insert([
            'name' => 'オペレーター１',
            'operator_code' => 'ope1',
            'email' => 'ope1@example.com',
            'password' => bcrypt('operator1')
        ]);
        */
        DB::table('operators')->insert([
            'name' => 'オペレーター２',
            'operator_code' => 'ope2',
            'email' => 'ope2@example.com',
            'password' => bcrypt('operator2')
        ]);
        DB::table('operators')->insert([
            'name' => 'オペレーター３',
            'operator_code' => 'ope3',
            'email' => 'ope3@example.com',
            'password' => bcrypt('operator3')
        ]);
    }
}
