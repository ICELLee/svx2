<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name'=>'admin'], ['label'=>'Administrator']);
        $userRole  = Role::firstOrCreate(['name'=>'user'],  ['label'=>'Standard User']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
