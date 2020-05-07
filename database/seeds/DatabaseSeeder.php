<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('roles')->insert([
            array('name' => 'pastor', 'level' => 1),
            array('name' => 'clerk', 'level' => 1),
            array('name' => 'accountant', 'level' => 1),
            array('name' => 'treasure', 'level' => 1),
            array('name' => 'homecell manager', 'level' => 1),
            array('name' => 'admin', 'level' => 1)
        ]);

        $password = bcrypt('qwerty');
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => $password,
            'role_id' => 6,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
