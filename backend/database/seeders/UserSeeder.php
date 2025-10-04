<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pendidikan;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uuid'=>'-',
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'nomor_telpon'=>'08994434947',
            //'tempat_lahir'=>'palembang',
            //'tgl_lahir'=>'1986-02-24',
            //'pekerjaan'=>'admin',
            //'pendidikan'=>Pendidikan::S1,
            'password' => 'password']);



        User::find(1)->roles()->attach(1,['user_modify'=>'su']);

        $superadmin=User::create([
                    'uuid'=>'-',
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'nomor_telpon'=>'08994434948',
                    //'tempat_lahir'=>'palembang',
                    //'tgl_lahir'=>'1986-02-24',
                    //'pekerjaan'=>'admin',
                    //'pendidikan'=>Pendidikan::S1,
                    'password' => 'password']);

                // Adding permissions via a role
        User::find(2)->roles()->attach(2,['user_modify'=>'su']);

        $petugasMedis=User::create([
                    'uuid'=>'-',
                    'name' => 'medis',
                    'email' => 'medis@gmail.com',
                    'nomor_telpon'=>'08994434948',
                    //'tempat_lahir'=>'palembang',
                    //'tgl_lahir'=>'1986-02-24',
                    //'pekerjaan'=>'admin',
                    //'pendidikan'=>Pendidikan::S1,
                    'password' => 'password']);

                // Adding permissions via a role
        User::find(3)->roles()->attach(3,['user_modify'=>'su']);
    }
}
