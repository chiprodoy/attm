<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        Role::create([
            'role_name'=>Role::SUPERADMIN,
        ]);
        //2
        Role::create([
            'role_name'=>Role::ADMIN,
        ]);
        //3
        Role::create([
            'role_name'=>Role::PETUGAS_MEDIS,
        ]);


    }
}
