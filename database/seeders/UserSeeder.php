<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Créer un utilisateur admin
        $admin = User::create([
            'name' => 'Ibrahima Lo',
            'email' => 'ibrahimalo407@gmail.com',
            'password' => bcrypt('molopomo'),
        ]);
        $admin->assignRole('admin');

        // Créer un utilisateur farmer
        $farmer = User::create([
            'name' => 'anna camara',
            'email' => 'annacamara@gmail.com',
            'password' => bcrypt('molopomo'),
        ]);
        $farmer->assignRole('farmer');
    }
}
