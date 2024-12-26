<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\User;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Assurez-vous d'avoir un utilisateur

        Crop::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);
    }
}
