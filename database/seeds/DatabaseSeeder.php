<?php

use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserSeeder::class);
        $this->call(RolePermSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);

        Model::reguard();
    }
}
