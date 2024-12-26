<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Modules\UserType\Entities\UserRole;

class AddDefaultRoleIntoUserRoleTable extends Migration
{

    public function up()
    {
        $users = User::select('id', 'role_id')->get();
        foreach ($users as $user) {
            $user->userRoles()->sync($user->role_id);

        }
    }


    public function down()
    {
        //
    }
}
