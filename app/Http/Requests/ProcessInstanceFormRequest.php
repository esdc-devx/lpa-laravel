<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessInstanceFormRequest extends FormRequest
{
    protected $processInstanceFormData;
    protected $formRequestClass;
    protected $namespace = '\App\Http\Requests';

    /**
     * Resolve form request class for based on process instance form data class.
     *
     * @return void
     */
    protected function resolveFormRequestClass()
    {
        $this->processInstanceFormData = request()->route('processInstanceFormData');
        $baseClassName = class_basename($this->processInstanceFormData);
        $formRequestClass = "{$this->namespace}\\{$baseClassName}FormRequest";
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

        return $this->user()->can('edit', $this->processInstanceFormData->processInstanceForm);
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
