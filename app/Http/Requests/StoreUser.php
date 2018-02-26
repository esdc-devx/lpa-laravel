<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User\User;
use App\Repositories\UserRepository;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to UserPolicy class to handle authorization.
        // @note: Check $this->method attribute to validate according action.
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'organization_units' => 'exists:organization_units,id'
        ];
    }

    protected function userNotFoundInLdap()
    {
        return app()->make(UserRepository::class)
            ->getUserFromLdap($this->username)
            ->count() === 0;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->userNotFoundInLdap()) {
                $validator->errors()->add('username', __('validation.custom.username.not-found'));
            }
        });
    }
}
