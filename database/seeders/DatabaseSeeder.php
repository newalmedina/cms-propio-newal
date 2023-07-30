<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(RolesSeeders::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminDashboardPremissionSeeder::class);
        $this->call(AdminSettingsPremissionSeeder::class);
        $this->call(AdminUsersPremissionSeeder::class);
        $this->call(AdminRolesPremissionSeeder::class);
        $this->call(AdminCentersPremissionSeeder::class);


        $this->call(InsertProvinceMunicipDataSeeder::class);
        $this->call(InsertCenterDataSeeder::class);
    }
}
