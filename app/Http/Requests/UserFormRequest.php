<?php

namespace App\Http\Requests;

use App\Models\User\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to UserPolicy class to handle authorization.
        switch ($this->method()) {
            case 'POST':
                return Gate::authorize('create', User::class);

            case 'PUT':
                return Gate::authorize('update', User::find($this->user));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // Create validation rules.
            case 'POST':
                return [
                    'username'             => 'required|unique:users',
                    'organizational_units' => 'exists:organizational_units,id',
                    'roles'                => 'exists:roles,id',
                ];

            // Update validation rules.
            case 'PUT':
                return [
                    'organizational_units' => 'exists:organizational_units,id',
                    'roles'                => 'exists:roles,id',
                ];

            default:
                return [];
        }
    }

    /**
     * Verify that the user exists in the active directory.
     *
     * @return bool
     */
    protected function userNotFoundInLdap()
    {
        return resolve('App\Repositories\UserRepository')
            ->getUserFromLdap($this->username) === null;
    }

    /**
     * Inject additional custom validation rules.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        switch ($this->method()) {
            // Custom rules for user creation.
            case 'POST':
                $validator->after(function ($validator) {
                    if ($this->userNotFoundInLdap()) {
                        $validator->errors()->add('username', __('validation.custom.username.not-found'));
                    }
                });
                break;
        }
    }
}
