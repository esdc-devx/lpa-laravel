<?php

use App\Models\User\User;
use App\Models\User\Role;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder
{
    protected $users;
    protected $adminRoleId;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        $this->adminRoleId = Role::getByKey('admin')->first()->id;
    }

    protected function createAdminUser($username)
    {
        $this->users->create([
            'username' => $username,
            'roles' => [$this->adminRoleId]
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        // Enforce eloquent guard attributes to ensure data integrity.
        Model::reguard();

        // Create admin account to boot the application and admin users.
        $adminUsers = collect(array_merge(
            [config('auth.admin.username')],
            config('auth.admin_users')
        ));

        $adminUsers->each(function($username) {
            $this->createAdminUser($username);
        });
    }
}
