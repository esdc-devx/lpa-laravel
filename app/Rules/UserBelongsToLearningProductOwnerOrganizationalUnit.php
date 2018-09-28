<?php

namespace App\Rules;

use App\Models\OrganizationalUnit;
use Illuminate\Contracts\Validation\Rule;

class UserBelongsToLearningProductOwnerOrganizationalUnit implements Rule
{
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        return OrganizationalUnit::getLearningProductOwnersFor(auth()->user())->pluck('id')
            ->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.in', [':attribute' => $this->attribute]);
    }
}
