<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_categories')->insert([
            [
                'name' => 'JavaScript',
            ],
            [
                'name' => 'PHP'
            ]
        ]);
    }
}
