<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; //passwordをhash化するときに必要なクラス

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::where('email', 'test@test.com')->first();
        if (!$user) {
            User::create([
                'name' => 'Shotaro Matsuya',
                'email' => 'test@test.com',
                'role' => 'admin',
                'password' => Hash::make('password')
            ]);
        }
    }
}
