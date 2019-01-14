<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationalUnitFormRequest;
use App\Models\OrganizationalUnit;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class OrganizationalUnitController extends APIController
{
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationalUnits = OrganizationalUnit::with('director', 'updatedBy')->get();

        return $this->respond([
            'organizational_units' => $organizationalUnits
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organizationalUnit = OrganizationalUnit::with('director', 'updatedBy')->findOrFail($id);
        $this->authorize('update', $organizationalUnit);

        return $this->respond([
            'organizational_unit' => $organizationalUnit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrganizationalUnitFormRequest  $request
     * @param  OrganizationalUnit  $organizationalUnit
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationalUnitFormRequest $request, OrganizationalUnit $organizationalUnit)
    {
        // Ensure that user cannot update any other attributes.
        $data = $request->only([
            'name_en',
            'name_fr',
            'email',
            'director',
        ]);

        // Find or create director user and update audit field.
        $organizationalUnit->update(
            array_merge($data, [
                'director' => $this->users->findOrCreate($data['director'])->id,
                'updated_by' => auth()->user()->id,
            ])
        );

        return $this->respond(
            $organizationalUnit
        );
    }
}
