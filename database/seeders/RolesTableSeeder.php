<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $connection = 'mysql2';

    public function run()
    {
        $agent = Role::connection('mysql2')->create([
            'name' => 'agent',
            'display_name' => 'agent',
            'description' => 'it s an admin',
        ]);
        
        $superviseur = Role::connection('mysql2')->create([
            'name' => 'superviseur',
            'display_name' => 'superviseur',
            'description' => 'just can see',
        ]);
    }
}
