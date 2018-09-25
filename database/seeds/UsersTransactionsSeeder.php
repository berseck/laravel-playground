<?php

use Illuminate\Database\Seeder;

class UsersTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UsersTransactions::class, 500)->create();
    }
}
