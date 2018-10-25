<?php

return [
    'title' => 'Gate One Approval',
    'is_entity_cancelled' => [
        'label'       => 'Project Assessment',
        'options'     => [
            'I would like to assess each form individually.',
            'I would like to cancel this project.',
        ],
        'description' => 'An indicator of whether or not the project has been cancelled.',
    ],
    'entity_cancellation_rationale' => [
        'label'       => 'Cancellation Rationale',
        'instruction' => 'Please provide an explanation if the project is cancelled.',
        'description' => 'The rationale for the project cancellation.',
    ],
];
