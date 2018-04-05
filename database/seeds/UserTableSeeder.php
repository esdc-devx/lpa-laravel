<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\User\Role;
use App\Models\OrganizationalUnit\OrganizationalUnit;
use App\Repositories\UserRepository;

class UserTableSeeder extends Seeder
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
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

        // Create admin account to boot the application.
        $role = Role::where('unique_key', 'admin')->firstOrFail();
        $this->users->create([
            'username' => config('auth.admin.username'),
            'roles' => [$role->id]
        ]);
    }
}
