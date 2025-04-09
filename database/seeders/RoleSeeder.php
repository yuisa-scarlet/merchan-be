<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'admin',
                'description' => 'Administrator role with full access',
            ],
            [
                'name' => 'user',
                'description' => 'Regular user role with limited access',
            ],
        ];

        collect($data)->each(fn ($data) => Role::create($data));
    }
}
