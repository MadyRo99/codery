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
            'email'    => 'madalin@gmail.com',
            'username' => 'MadyRo',
            'password' => Hash::make('password'),
            'avatar'   => "madalin.png",
            'role'     => 2,
        ]);
    }
}
