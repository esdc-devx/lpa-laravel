<?php

namespace App\Http\Requests;

use App\Models\Process\ProcessNotification;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProcessNotificationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to ProcessNotificationPolicy class to handle authorization.
        switch ($this->method()) {
            case 'PUT':
                return Gate::authorize('update', $this->processNotification);
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
        switch ($this->method()) {
            // Update validation rules.
            case 'PUT':
                return [
                    'name_en'    => "required|string|max:150|unique:process_notifications,name_en,{$this->processNotification->id},id,deleted_at,NULL",
                    'name_fr'    => "required|string|max:150|unique:process_notifications,name_fr,{$this->processNotification->id},id,deleted_at,NULL",
                    'subject_en' => 'required|string|max:150',
                    'subject_fr' => 'required|string|max:150',
                    'body_en'    => 'required|string|max:2500',
                    'body_fr'    => 'required|string|max:2500',
                ];
        }
    }
}
