<?php

namespace App\Http\Resources;

class ProcessInstanceFormResource extends BaseResource
{
    public function context($request)
    {
        $processInstance = $this->step->processInstance;
        $processEntity = $processInstance->entity;

        return array_merge([
            'process_instance' => $processInstance,
            'process_entity'   => $processEntity,
        ], $this->resolveFormDataResource());
    }

    protected function resolveFormDataResource() {
        // Resolve form data resource class.
        $formName = studly_case($this->definition->name_key);
        $formDataResourceClass = "App\Http\Resources\\{$formName}Resource";

        // Append form data context.
        if (class_exists($formDataResourceClass)) {
            return (new $formDataResourceClass($this->formData))->context(request());
        }

        return [];
    }
}
