<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_setting')->insert(
            ['user_id' => 1]
        );
    }
}
