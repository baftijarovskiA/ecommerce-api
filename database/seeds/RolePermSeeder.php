<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        $roles = array(['name'=>'admin','description'=>'Admin role']);

        $permissions = array(['name'=>'full access']);

        foreach ($roles as $item)
        {
            Role::create($item);
        }

        foreach ($permissions as $item)
        {
            Permission::create($item);
        }

    }
}
