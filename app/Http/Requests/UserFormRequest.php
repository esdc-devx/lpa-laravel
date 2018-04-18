<?php

namespace App\Http\Requests;

use App\Models\User\User;
use App\Repositories\UserRepository;
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
            case 'GET':     return true;
            case 'POST':    return $this->user()->can('create', User::class);
            case 'PUT':     return $this->user()->can('update', User::find($this->user));
            case 'DELETE':  return $this->user()->can('delete', User::class);
            default:        return false;
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
            default: return [];
        }
    }

    /**
     * Verify that the user exists in the active directory.
     *
     * @return bool
     */
    protected function userNotFoundInLdap()
    {
        return app()->make(UserRepository::class)
            ->getUserFromLdap($this->username) === null;
    }

    /**
     * Verify if the user being edited is the main admin account.
     *
     * @return bool
     */
    protected function userIsAdminAccount()
    {
        return strcasecmp(
            app()->make(UserRepository::class)->getById($this->route('user'))->username,
            config('auth.admin.username')
        ) === 0;
    }

    /**
     * Inject additional custom validation rules.
     *
     * @return bool
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

            // Custom rules for user edit.
            case 'PUT':
                $validator->after(function ($validator) {
                    if ($this->userIsAdminAccount()) {
                        throw new AuthorizationException(__('errors.error_edit_admin'));
                    }
                });
                break;
        }
    }
}
