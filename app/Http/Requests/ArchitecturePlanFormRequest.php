<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchitecturePlanFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to ProcessInstanceFormRequest authorize method.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // If form is submitted, enforce validation rules.
        if (collect($this->segments())->last() == 'submit') {
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
     * @return void
     */
    public function withValidator($validator)
    {
        // If form is submitted, enforce validation rules.
        if (collect($this->segments())->last() == 'submit') {
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
