<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use App\Repositories\UserRepository;
use App\Repositories\OrganizationalUnitRepository;
use App\Http\Resources\UserLdap;
use App\Http\Requests\UserFormRequest;

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $results = $this->users->searchLdap($request->get('name'));
        return $this->respond(UserLdap::collection($results));
    }

    /**
     * Display current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function current(Request $request)
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
        // @todo: Add option to display deleted users?
        $limit = request()->get('limit') ? : self::ITEMS_PER_PAGE;
        return $this->respondWithPagination(
            $this->users->getPaginated($limit)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return user creation form data.
        return $this->respond([
            'organizational_units' => $this->organizationalUnits->getAll()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        return $this->respond(
            $this->users->create($request->all())
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
        // Return specific user.
        return $this->respond(
            $this->users->getById($id)
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
        // @todo: Return user edit form.
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, int $id)
    {
        $data = $request->only('organizational_units');
        return $this->respond(
            $this->users->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Http\Requests\UserFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFormRequest $request, int $id)
    {
        // If request has force parameter, permenantely delete the user.
        $method = $request->get('force') ? 'forceDelete' : 'delete';
        return $this->respond(
            $this->users->{$method}($id)
        );
    }
}
