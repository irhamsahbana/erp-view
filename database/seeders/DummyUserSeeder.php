<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(20)->create();

        $users = [
            [
                'username' => 'owner',
                'password' => 'owner',
                'role' => 'owner',
                'branch_id' => null,
            ],
            [
                'username' => 'admin1',
                'password' => 'admin1',
                'role' => 'admin',
                'branch_id' => 1,
            ],
            [
                'username' => 'admin2',
                'password' => 'admin2',
                'role' => 'admin',
                'branch_id' => 2,
            ],
            [
                'username' => 'branch_head1',
                'password' => 'branch_head1',
                'role' => 'branch_head',
                'branch_id' => 1,
            ],
            [
                'username' => 'branch_head2',
                'password' => 'branch_head2',
                'role' => 'branch_head',
                'branch_id' => 2,
            ],
            [
                'username' => 'accountant1',
                'password' => 'accountant1',
                'role' => 'accountant',
                'branch_id' => 1,
            ],
            [
                'username' => 'accountant2',
                'password' => 'accountant2',
                'role' => 'accountant',
                'branch_id' => 2,
            ],
            [
                'username' => 'cashier1',
                'password' => 'cashier1',
                'role' => 'cashier',
                'branch_id' => 1,
            ],
            [
                'username' => 'cashier2',
                'password' => 'cashier2',
                'role' => 'cashier',
                'branch_id' => 2,
            ],
            [
                'username' => 'material1',
                'password' => 'material1',
                'role' => 'material',
                'branch_id' => 1,
            ],
            [
                'username' => 'material2',
                'password' => 'material2',
                'role' => 'material',
                'branch_id' => 2,
            ],
        ];

        foreach ($users as $user) {
            $newUser = new User();

            $newUser->username = $user['username'];
            $newUser->password = Hash::make($user['password']);
            $newUser->role = $user['role'];
            $newUser->branch_id = $user['branch_id'];
            $newUser->save();
        }
    }
}
