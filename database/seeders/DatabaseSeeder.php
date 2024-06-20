<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ShieldSeeder::class);

        $user = User::factory()->create([
            'name' => 'Faysal Ahamed',
            'email' => 'faysal@surovigroup.net',
            'password' => '$urovigroup',
        ]);
        $user->assignRole('Super Admin');
    }
}
