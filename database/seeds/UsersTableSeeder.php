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
			'first_name'    => 'Ahmed',
			'last_name'     => 'Gad',
			'email'         => 'eng.ahmedmgad@gmail.com',
			'password'      => bcrypt(123456),
		]);

		
    }
}
