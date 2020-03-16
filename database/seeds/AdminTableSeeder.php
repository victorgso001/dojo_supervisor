<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create([
            'username' => 'test',
            'password' => md5('123'),
            'user' => 'Test Admin',
        ]);
    }
}
