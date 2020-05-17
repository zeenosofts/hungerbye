<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=new \App\User();
        $user->first_name="Zohaib";
        $user->last_name="Azhar";
        $user->password= md5('1234567');
        $user->email= "zohaibazhar359@gmail.com";
        $user->status= true;
        $user->save();
//Saving Role
        $owner = new \App\Role();
        $owner->name         = 'superadmin';
        $owner->display_name = 'CEO'; // optional
        $owner->description  = 'User is the CEO'; // optional
        $owner->save();
        $user->attachRole($owner);
        ///
        $user1 = new \App\Role();
        $user1->name         = 'editor';
        $user1->display_name = 'Editor'; // optional
        $user1->description  = 'User is the Editor'; // optional
        $user1->save();

//        $user4 = new \App\Role();
//        $user4->name         = 'admin';
//        $user4->display_name = 'Admin'; // optional
//        $user4->description  = 'User is the Admin'; // optional
//        $user4->save();

        $user2 = new \App\Role();
        $user2->name         = 'donor';
        $user2->display_name = 'Donor'; // optional
        $user2->description  = 'User is the Donor'; // optional
        $user2->save();

        $user3 = new \App\Role();
        $user3->name         = 'partner';
        $user3->display_name = 'Partner'; // optional
        $user3->description  = 'User is the Partner'; // optional
        $user3->save();

        $user4 = new \App\Role();
        $user4->name         = 'staff';
        $user4->display_name = 'Partners Staff'; // optional
        $user4->description  = 'User is the Staff of Partner'; // optional
        $user4->save();

        $user5 = new \App\Role();
        $user5->name         = 'manager';
        $user5->display_name = 'Partners Manager'; // optional
        $user5->description  = 'User is the Manager of Partner'; // optional
        $user5->save();
    }
}
