<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r1 = Role::create(['name' => 'Admin']);
        $r2 = Role::create(['name' => 'Moderator']);
        $r3 = Role::create(['name' => 'User']);
    }
}
