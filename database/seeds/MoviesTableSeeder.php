<?php

use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies')->insert([
            'name' => 'Bumblebee',
            'function_init_date' => '2019-01-21',
            'function_end_date' => '2019-01-25',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
    }
}
