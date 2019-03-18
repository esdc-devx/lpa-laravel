<?php

namespace App\Models\LearningProduct\Development;

use App\Models\Process\ProcessInstanceFormDataModel;

class OperationalDetails extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'disclaimer_content',
        'external_delivery',
        'maximum_number_of_participant_per_offering',
        'minimum_number_of_participant_per_offering',
        'note_content',
        'number_of_breakout_room_per_offering',
        'number_of_virtual_producers_per_offering',
        'optimal_number_of_participant_per_offering',
        'process_instance_form_id',
        'process_instance_id',
        'session_template',
        'should_be_published',
        'summary_content',
        'waiting_list_maximum_size',
    ];

    protected $with = [
        'documents',
        'guestSpeakers',
        'instructors',
        'materials',
        'rooms',
    ];

    protected $casts = [
        'external_delivery'   => 'boolean',
        'should_be_published' => 'boolean',
    ];

    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }

    public function guestSpeakers()
    {
        return $this->hasMany(GuestSpeaker::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'documents',
            'guestSpeakers',
            'instructors',
            'materials',
            'rooms',
        ]);

        parent::saveFormData($data);
    }
}
