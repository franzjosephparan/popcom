<?php

use App\Item;
use Illuminate\Database\Seeder;
use App\User;
use App\Facility;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->facility_id = -1;
        $user->first_name = 'admin';
        $user->last_name = 'admin';
        $user->contact_number = '123123123';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('popcom2020');
        $user->roles = 'admin';
        $user->status = '1';
        $user->created_by = -1;
        $user->api_token = Str::random(60);
        $user->save();
    }
}
