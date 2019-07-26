<?php

use Illuminate\Database\Seeder;
use App\Admin;

class adminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create( [
			'name'=>'Admin',
			'email'=>'admin@admin.com',
			'password'=>bcrypt('123123123'),
			'remember_token'=>NULL
		]);


    }
}
