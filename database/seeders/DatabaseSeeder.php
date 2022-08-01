<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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

        Category::factory(100)->create();
        $this->call(UserSeeder::class);
        $this->call(RolesSeeders::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminUsersPremissionSeeder::class);
        $this->call(AdminRolesPremissionSeeder::class);
        $this->call(AdminCategoriesPremissionSeeder::class);
    }
}
