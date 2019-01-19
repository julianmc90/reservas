<?php

use Illuminate\Database\Seeder;

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
            'identification'=> '123456789',
            'name' => 'Julian Andres',
            'last_name' => 'Muñoz Cardozo',
            'email' => 'julianmc90@gmail.com',
            'password' => bcrypt('1234'),
            'created_at'=>now(),
            'updated_at'=>now()
        ]);
    }
}
