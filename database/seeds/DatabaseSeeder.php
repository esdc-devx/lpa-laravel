<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call([
            RoleTableSeeder::class,
            UserTableSeeder::class,
            OrganizationalUnitTableSeeder::class,
            StatesTableSeeder::class,
            ProjectTableSeeder::class,
            ProcessProjectApprovalSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
