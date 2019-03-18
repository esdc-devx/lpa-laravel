<?php

return [
    'tabs' => [
        'overall_assessment' => 'Overall Assessment',
        // form tabs ommited, since they are dynamically added by the entity title
    ],
    'assessment_date' => [
        'label'       => 'Assessment Date',
        'description' => 'The date on which the assessment was made.',
    ],
    'process_form_decision_id' => [
        'label'       => 'Decision',
        'description' => 'The decision on the :assessed_form_title form.',
    ],
    'assessment_comments' => [
        'label'       => 'Comments',
        'instruction' => 'Please provide an explanation if adjustments are required.',
        'description' => 'Comments supporting the decision on the :assessed_form_title form.',
    ],
    'entity_cancellation' => [
        'project'=> [
            'cancel_entity'    => 'Cancelling a project is a critical and definitive action. This action will automatically take place upon submitting this form.',
            'is_entity_cancelled' => [
                'label'         => 'Project Assessment',
                'options'       => [
                    'false'     => 'I would like to assess each form individually.',
                    'true'      => 'I would like to cancel this project.',
                ],
                'description'   => 'An indicator of whether or not the project has been cancelled.',
            ],
            'entity_cancellation_rationale' => [
                'label'         => 'Cancellation Rationale',
                'instruction'   => 'Please provide an explanation if the project is cancelled.',
                'description'   => 'The rationale for the project cancellation.',
            ],
        ],
        'course' => [
            'cancel_entity'       => 'Cancelling a course is a critical and definitive action. This action will automatically take place upon submitting this form.',
            'is_entity_cancelled' => [
                'label'   => 'Course Assessment',
                'options' => [
                    'false' => 'I would like to assess each form individually.',
                    'true'  => 'I would like to cancel this course.',
                ],
                'description' => 'An indicator of whether or not the course has been cancelled.',
            ],
            'entity_cancellation_rationale' => [
                'label'       => 'Cancellation Rationale',
                'instruction' => 'Please provide an explanation if the course is cancelled.',
                'description' => 'The rationale for the course cancellation.',
            ],
        ],
    ],
];
