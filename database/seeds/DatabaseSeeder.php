<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        \DB::table('user')
            ->insert(
                [
                    'username'=>'jamal.apriadi',
                    'password'=>bcrypt('welcome'),
                    'lvl'=>'Admin',
                    'perusahaan'=>'paturaw'
                ]
            );
    }
}
