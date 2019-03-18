<?php

return [
    'form_title' => 'Offering and Enrolment Estimates',
    'tabs'  => [
        'participants' => 'Participants',
        'regions'      => 'Regions',
        'comments'     => 'Comments',
    ],
    'form_section_groups' => [
        'region' => 'Region|Regions',
    ],
    'estimated_average_annual_participant_number' => [
        'label'       => 'Estimated Average Annual Participant Number',
        'description' => 'The estimated average number of participants per year.',
    ],
    'regions' => [
        'region' => [
            'label'       => 'Region',
            'description' => 'The learning product offering region.',
        ],
        'regional_annual_bilingual_offering_number' => [
            'label'       => 'Estimated Regional Bilingual Offerings per Year',
            'description' => 'The estimated number of bilingual offerings per year in the corresponding region.',
        ],
        'regional_annual_english_offering_number' => [
            'label'       => 'Estimated Regional English Offerings per Year',
            'description' => 'The estimated number of English offerings per year in the corresponding region.',
        ],
        'regional_annual_french_offering_number' => [
            'label'       => 'Estimated Regional French Offerings per Year',
            'description' => 'The estimated number of French offerings per year in the corresponding region.',
        ],
        'regional_annual_simultaneous_interpretation_offering_number' => [
            'label'       => 'Estimated Regional Offerings with Simulaneous Interpretation per Year',
            'description' => 'The estimated number of offerings with simultaneous interpretation (sign language) per year in the corresponding region.',
        ],
    ],
    'comments' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the learning product.',
    ],
];
