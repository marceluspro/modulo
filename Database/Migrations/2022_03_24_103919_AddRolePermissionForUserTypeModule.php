<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddRolePermissionForUserTypeModule extends Migration
{
    public function up()
    {
        $routes = [
            ['name' => 'User Type', 'route' => 'usertype', 'parent_route' => null, 'type' => 1, 'module' => 'UserType'],
            ['name' => 'List', 'route' => 'usertype.list', 'parent_route' => 'usertype', 'type' => 2, 'module' => 'UserType'],
        ];

        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }
    }

    public function down()
    {
        //
    }
}
