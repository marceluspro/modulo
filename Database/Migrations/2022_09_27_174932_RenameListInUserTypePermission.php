<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class RenameListInUserTypePermission extends Migration
{
    public function up()
    {


        $item = Permission::where('route', 'usertype.list')->first();
        if ($item) {
            $item->name = 'Role Assign';
            $item->save();
        }
    }

    public function down()
    {
        //
    }
}
