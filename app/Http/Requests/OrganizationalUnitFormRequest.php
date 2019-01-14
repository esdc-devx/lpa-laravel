<?php

namespace App\Http\Requests;

use App\Models\OrganizationalUnit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationalUnitFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to OrganizationalUnitPolicy class to handle authorization.
        switch ($this->method()) {
            case 'PUT':
                return Gate::authorize('update', $this->organizationalUnit);
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
        switch ($this->method()) {
            // Update validation rules.
            case 'PUT':
                return [
                    'name_en'  => "required|string|max:150|unique:organizational_units,name_en,{$this->organizationalUnit->id},id,deleted_at,NULL",
                    'name_fr'  => "required|string|max:150|unique:organizational_units,name_fr,{$this->organizationalUnit->id},id,deleted_at,NULL",
                    'email'    => 'required|string|email|max:250',
                    'director' => 'required|string',
                ];
        }
    }
}
