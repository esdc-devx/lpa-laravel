<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserLdap;
use App\Http\Requests\UserFormRequest;
use App\Models\User\Role;
use App\Models\User\User;
use App\Repositories\OrganizationalUnitRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends APIController
{
    protected $users;
    protected $organizationalUnits;

    public function __construct(UserRepository $users, OrganizationalUnitRepository $organizationalUnits)
    {
        $this->users = $users;
        $this->organizationalUnits = $organizationalUnits;
    }

    /**
     * Search users into LDAP repository.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->authorize('search', User::class);

        $results = $this->users->searchLdap($request->get('name'));
        return $this->respond(UserLdap::collection($results));
    }

    /**
     * Display current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function current()
    {
        return $this->respond($this->users->getCurrent());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['organizationalUnits', 'roles', 'createdBy', 'updatedBy'])
            ->where('username', '!=', config('auth.admin.username'))
            ->get();

        return $this->respond($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        // Return user creation form data.
        return $this->respond([
            'organizational_units' => $this->organizationalUnits->getAll(),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        // Retrieve only the necessary attributes from the request.
        $data = $request->only([
            'username',
            'organizational_units',
            'roles',
        ]);

        return $this->respond(
            $this->users->create($data)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Return specific user with all of its relationships.
        return $this->respond(
            $this->users->with('all')->getById($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with(['organizationalUnits', 'roles', 'createdBy', 'updatedBy'])->findOrFail($id);
        $this->authorize('update', $user);

        // Return user edit form data.
        return $this->respond([
            'user'                 => $user,
            'organizational_units' => $this->organizationalUnits->getAll(),
            'roles'                => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserFormRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, int $id)
    {
        // Ensure that user cannot update any other attributes.
        $data = $request->only([
            'organizational_units',
            'roles',
        ]);

        return $this->respond(
            $this->users->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('delete', $this->users->getById($id));

        // If request has force parameter, permenantely delete the user.
        $method = $request->get('force') ? 'forceDelete' : 'delete';
        return $this->respond(
            $this->users->{$method}($id)
        );
    }
}
