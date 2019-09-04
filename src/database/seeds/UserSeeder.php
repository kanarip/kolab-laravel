<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'email' => 'jdoe@example.org',
                'password' => bcrypt('simple123')
            ]
        );

        // 10'000 users result in a table size of 11M
        factory(User::class, 100)->create();
    }
}