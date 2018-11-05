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
];
