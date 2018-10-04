<?php

namespace App\Http\Requests;

use App\Models\LearningProduct\LearningProduct;
use App\Models\Project\Project;
use App\Rules\OrganizationalUnitIsLearningProductOwner;
use App\Rules\UserBelongsToOrganizationalUnit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class LearningProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to LearningProductPolicy class to handle authorization.
        switch ($this->method()) {
            case 'POST':
                return Gate::authorize('create', LearningProduct::class);

            case 'PUT':
                return Gate::authorize('update', LearningProduct::find($this->learningProduct));
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
            'project_id'             => 'required|integer',
            'name'                   => 'required|max:175|unique:learning_products,name,' . $this->learningProduct,
            'organizational_unit_id' => ['required', new UserBelongsToOrganizationalUnit, new OrganizationalUnitIsLearningProductOwner],
            'type_id'                => 'required|integer',
            'sub_type_id'            => 'required|integer',
            'primary_contact'        => 'required|string',
            'manager'                => 'required|string',
        ];
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
            // Custom rules for learning product creation.
            case 'POST':
                $validator->after(function ($validator) {
                    // Ensure selected project is still a valid choice.
                    if (! $project = Project::availableForLearningProductCreation()->firstWhere('id', $this->project_id)) {
                        $validator->errors()->add('project_id', trans('validation.custom.learning_product.invalid_project'));
                    }

                    // Ensure selected learning product type is still a valid choice.
                    if (! $project || ! $project->availableLearningProductTypes->firstWhere('sub_type_id', $this->sub_type_id)) {
                        $validator->errors()->add('type_id', trans('validation.custom.learning_product.invalid_type'));
                    }
                });
                break;
        }
    }
}
