<?php

return [
    'title' => 'Assessment',
    'tabs' => [
        'assessment'      => 'Assessment',
        // form tabs ommited, since they are dynamically added by the entity title
    ],
    'is_process_cancelled' => [
        'label'       => 'Project Cancellation',
        'option'      => 'I would like to cancel this project.',
        'description' => 'An indicator of whether or not the project has been cancelled.',
    ],
    'process_cancellation_rationale' => [
        'label'       => 'Cancellation Rationale',
        'instruction' => 'Please provide an explanation if the project is cancelled.',
        'description' => 'The rationale for the project cancellation.',
    ],
    'process_form_decision_id' => [
        'label'       => 'Decision',
        'description' => 'The decision on the Architecture Plan.',
    ],
    'assessment_comment' => [
        'label'       => 'Comments',
        'instruction' => 'Please provide an explanation if adjustments are required.',
        'description' => 'Comments supporting the decision on the Architecture Plan.',
    ],
    'assessment_date' => [
        'label'       => 'Assessment Date',
        'description' => 'The date on which the assessment was made.',
    ]
];
