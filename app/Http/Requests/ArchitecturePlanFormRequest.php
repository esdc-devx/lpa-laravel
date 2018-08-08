<?php

namespace App\Http\Requests;

class ArchitecturePlanFormRequest extends ProcessInstanceFormDataRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            return [
                'planned_products'               => 'array|min:1',
                'planned_products.*.type_id'     => 'required|integer',
                'planned_products.*.sub_type_id' => 'required|integer',
                'planned_products.*.quantity'    => 'required|integer|min:1|max:100',
                'comment'                        => 'string|nullable|max:2500',
            ];
        }

        return [];
    }

    /**
     * Inject additional custom validation rules.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($this->submitted()) {
            $validator->after(function ($validator) {
                // Ensure there is only one planned product of the same type.
                $productIds = collect([]);
                foreach ($this->planned_products as $key => $plannedProduct) {
                    $productId = "{$plannedProduct['type_id']}.{$plannedProduct['sub_type_id']}";
                    if ($productIds->contains($productId)) {
                        $validator->errors()->add("planned_products.$key.type_id", __('validation.custom.planned_products.unique'));
                        continue;
                    }
                    $productIds->push($productId);
                }
            });
        }
    }
}
