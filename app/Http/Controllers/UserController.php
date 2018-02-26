<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use App\Repositories\UserRepository;
use App\Repositories\OrganizationUnitRepository;
use App\Http\Resources\UserLdap;
use App\Http\Requests\StoreUser;

class UserController extends ApiController
{
    protected $users;
    protected $organizationUnits;

    public function __construct(UserRepository $users, OrganizationUnitRepository $organizationUnits)
    {
        $this->users = $users;
        $this->organizationUnits = $organizationUnits;
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
            'organization_units' => $this->organizationUnits->getAll()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
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
        // Return user edit form.
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // @note: Should be moved to UserFormRequest class.
        $this->authorize('update', $this->users->getById($id));

        $data = $request->all();
        return $this->respond(
            $this->users->update($id, $data)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // @note: Should be moved to UserFormRequest class.
        $this->authorize('delete', auth()->user(), User::class);

        // If request has force parameter, permenantely delete the user.
        $method = $request->get('force') ? 'forceDelete' : 'delete';
        return $this->respond(
            $this->users->{$method}($id)
        );
    }
}
