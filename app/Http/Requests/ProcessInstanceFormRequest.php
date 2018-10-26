<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProcessInstanceFormRequest extends FormRequest
{
    protected $processInstanceForm;
    protected $formRequestClass;
    protected $namespace = '\App\Http\Requests';

    /**
     * Resolve form request class based on process instance form data class.
     *
     * @return void
     */
    protected function resolveFormRequestClass()
    {
        $this->processInstanceForm = $this->route('processInstanceFormData');

        // Get form data class (i.e. BusinessCase, PlannedProductList, GateOneApproval etc.).
        $formDataClass = class_basename($this->processInstanceForm->formData()->getRelated());

        // Resolve form request class according to form data class. (i.e. BusinessCaseFormRequest, PlannedProductListFormRequest, etc.).
        $formRequestClass = "{$this->namespace}\\{$formDataClass}FormRequest";
        if (class_exists($formRequestClass)) {
            $this->formRequestClass = resolve($formRequestClass);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->resolveFormRequestClass();

        // Get last segment of request url (either edit or submit).
        $action = collect($this->segments())->last();

        return Gate::authorize($action, $this->processInstanceForm);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Defer to form request rules.
        if ($this->formRequestClass && method_exists($this->formRequestClass, 'rules')) {
            return $this->formRequestClass->rules();
        }
        return [];
    }
}
