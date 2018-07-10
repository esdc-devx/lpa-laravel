<?php

namespace App\Http\Requests;

use App\Models\Project\Project;
use Illuminate\Foundation\Http\FormRequest;

class ProjectFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to ProjectPolicy class to handle authorization.
        switch ($this->method()) {
            case 'POST':
                return $this->user()->can('create', Project::class);

            case 'PUT':
                return $this->user()->can('update', Project::find($this->project));
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => 'required|max:175|unique:projects,name,' . $this->project,
            'organizational_unit' => 'required|exists:organizational_units,id|in:' . $this->validOrganizationalUnitChoices(),
        ];
    }

    /**
     * Return a list of valid organizational unit ids based on
     * user role and his own organizational units.
     *
     * @return string
     */
    public function validOrganizationalUnitChoices()
    {
        $validOrganizationalUnitIds = resolve('App\Repositories\OrganizationalUnitRepository')
            ->getOwnersFor($this->user())
            ->keyBy('id')
            ->toArray();

        return join(',', array_keys($validOrganizationalUnitIds));
    }
}
