<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = User::create([
            'name' => 'agent',
            'email' => 'agent@gmail.com',
            'password' => Hash::make('agent'),
        ]);

        $agent->attachRole('agent');

    }
}
