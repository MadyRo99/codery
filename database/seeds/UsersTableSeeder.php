<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email'    => 'madalindanescu99@gmail.com',
            'username' => 'MadalinRo',
            'password' => Hash::make('madyettibuc99'),
            'avatar'   => "blogger.png",
            'role'     => 2,
        ]);
    }
}
