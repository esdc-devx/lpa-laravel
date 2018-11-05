<?php

namespace App\Http\Requests;

class PlannedProductListFormRequest extends ProcessInstanceFormDataRequest
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
                'comments'                       => 'string|nullable|max:2500',
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
                collect($this->planned_products)->mapToGroups(function ($product, $index) {
                    return ["{$product['type_id']}.{$product['sub_type_id']}" => $index];
                })
                ->each(function ($grouped) use ($validator) {
                    // If combination of type and sub-type id has more than one item, flag them as invalid.
                    if ($grouped->count() > 1) {
                        $grouped->each(function ($key) use ($validator) {
                            $validator->errors()->add(
                                "planned_products.$key.type_id",
                                __('validation.distinct', ['attribute' => __('validation.attributes.learning_product_type')])
                            );
                        });
                    }
                });
            });
        }
    }
}
