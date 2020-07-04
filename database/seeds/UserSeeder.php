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

        // test facility & user
        $user = new User();
        $user->first_name = 'usertoledo';
        $user->last_name = 'usertoledo';
        $user->contact_number = '123123123';
        $user->email = 'usertoledo@gmail.com';
        $user->password = Hash::make('popcom2020');
        $user->roles = 'representative';
        $user->status = 1;
        $user->created_by = 1;
        $user->api_token = Str::random(60);
        $user->save();

        $facility = new Facility();
        $facility->user_id = 2;
        $facility->facility_name = 'toledo facility';
        $facility->address = 'sangi toledo city';
        $facility->region = '7';
        $facility->province = 'cebu';
        $facility->longitude = '1';
        $facility->latitude = '1';
        $facility->facility_type = 'RHU';
        $facility->status = 1;
        $facility->created_by = 1;
        $facility->save();

        // test item
        $item = new Item();
        $item->item_sku = 'item_sku1';
        $item->item_name = 'chocolate trust condom';
        $item->item_description = 'chocolate flavor';
        $item->category = 'male condom';
        $item->status = 1;
        $item->created_by = 1;
        $item->save();
    }
}
