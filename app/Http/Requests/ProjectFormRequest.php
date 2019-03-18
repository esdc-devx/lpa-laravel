<?php

namespace App\Http\Requests;

use App\Models\Project\Project;
use App\Policies\ProjectPolicy;
use App\Rules\OrganizationalUnitIsLearningProductOwner;
use App\Rules\UserBelongsToOrganizationalUnit;
use Gate;
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
                return Gate::authorize('create', Project::class);

            case 'PUT':
                return Gate::authorize('update', Project::find($this->project));
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
            'name'                   => "required|max:175|unique:projects,name,{$this->project},id,deleted_at,NULL",
            'organizational_unit_id' => ['required', new UserBelongsToOrganizationalUnit, new OrganizationalUnitIsLearningProductOwner],
            'outline'                => 'required|max:1250',
        ];
    }
}
