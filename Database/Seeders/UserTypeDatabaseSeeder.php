<?php

namespace Modules\UserType\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTypeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::select('id', 'role_id')->get();
        foreach ($users as $user) {
            $user->userRoles()->sync($user->role_id);
        }
    }
}
