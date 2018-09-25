<?php

use Illuminate\Database\Seeder;

class UsersVCSTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::get();

        foreach ($users as $user) {
            factory(App\Models\UsersVCS::class)->create(
                ['user_id' => $user->id]
            );
        }
    }
}
