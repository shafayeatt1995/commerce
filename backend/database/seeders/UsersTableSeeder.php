<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Md. Admin',
            'role_id' => '1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123'),
            'email_verified_at' => Carbon::now(),
            'social' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Md. Customer',
            'role_id' => '2',
            'email' => 'customer@customer.com',
            'password' => bcrypt('123'),
            'email_verified_at' => Carbon::now(),
            'social' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
