<?php

use App\Models\User\User;
use App\Models\User\Role;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
            'password' => $username == config('auth.admin.username') ? bcrypt(config('auth.admin.password')) : null,
            'roles'    => [$this->adminRoleId]
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

        // Create admin accounts to boot the application.
        collect(array_merge(
            [config('auth.admin.username')], config('auth.admin_users')
        ))
        ->each(function($username) {
            $this->createAdminUser($username);
        });

        // Once admin accounts are created, authenticate as admin.
        Auth::login(User::whereUsername(config('auth.admin.username'))->first());

        // Update users audit information now that we are authenticated as admin.
        User::all()->each->updateAudit();
    }
}
