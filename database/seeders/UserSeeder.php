<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $is_exist_user = User::withTrashed()->where('email', 'admin@work.com')->first();
        if(!$is_exist_user){
            $user = [
                'name'=>'Superadmin',
                'gender'=>1,
                'email'=>'admin@work.com',
                'is_admin'=>Constants::USER_ADMIN,
                'password'=>Hash::make('12345678'),
            ];
            User::create($user);
        }else{
            if(!isset($is_exist_user->deleted_at)){
                echo "Current user is exist status deleted";
            }else{
                echo "Current user is exist status active";
            }
        }
    }
}
