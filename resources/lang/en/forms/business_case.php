<?php

return [
    'title' => 'Business Case',
    'tabs'  => [
        'project_objective'              => 'Project Objective',
        'proposed_solution'              => 'Proposed Solution',
        'school_priorities'              => 'School Priorities',
        'target_audience'                => 'Target Audience',
        'departmental_results_framework' => 'Departmental Results Framework',
        'costs'                          => 'Costs and Resources',
        'internal_resources'             => 'Internal Resources',
        'risk'                           => 'Risks',
        'comments'                       => 'Comments',
    ],
    'form_section_groups' => [
        'risk'      => 'Risk|Risks',
        'spending'  => 'Cost Breakdown|Cost Breakdown',
    ],
    'business_issue' => [
        'label'       => 'Business Issues or Learning Gaps',
        'instruction' => 'What are the business issues, problems or learning gaps that the School is trying to solve?',
        'description' => 'The business issues, problems or learning gaps motivating this project.',
        'help'        => 'For example: A functional community has identified a crucial learning gap that isn’t addressed by the School’s current offering.',
    ],
    'short_term_learning_response' => [
        'label'       => 'Short Term Learning Response (0-3 months)',
        'instruction' => 'Please provide the deliverables for the next 3 months following project approval.',
        'description' => 'The short term learning response strategy (0-3 months).',
        'help'        => 'For example: Increased marketing of existing or related products, events, webcasts',
    ],
    'medium_term_learning_response' => [
        'label'       => 'Medium Term Learning Response (3-6 months)',
        'instruction' => 'Please provide the deliverables for the next 3 to 6 months following project approval.',
        'description' => 'The medium term learning response strategy (3-6 months).',
        'help'        => 'For example: videos, micro-learning, creating a new product from existing products (e.g. pulling and creating a stand-alone module from an existing course)',
    ],
    'long_term_learning_response' => [
        'label'       => 'Long Term Learning Response (6-18 months)',
        'instruction' => 'Please provide the deliverables for the next 6 to 18 months following project approval.',
        'description' => 'The long term learning solution (6-18 months).',
        'help'        => 'For example: online or in-class course that will be added to the School’s curriculum',
    ],
    'school_priorities' => [
        'label'       => 'School Priorities',
        'instruction' => 'Please select the priorities that apply to your project.',
        'description' => 'The list of School priorities addressed by this project.',
    ],
    'request_origins' => [
        'label'       => 'Origins of the Request',
        'instruction' => 'Why are you submitting this project?',
        'description' => 'The circumstances leading to the creation or modification of this project.',
        'help'        => 'For example: Was there a change in policy that requires a learning product transformation? Was there a new Government of Canada priority that needs to be addressed by learning products?',
    ],
    'request_origin_other' => [
        'label'       => 'Other Origins of the Request',
    ],
    'is_required_training' => [
        'label'       => 'Required Training / Part of Certification',
        'instruction' => 'Please indicate whether the Learning Products included in this project are considered "required training" and/or "part of a certification"?',
        'description' => 'The learning products included in the project are considered as "required training" or "part of a certification".',
        'help'        => 'The definition of "required training" can be found in the Treasury Board Policy on Learning, Training, and Development.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Expected number of participants (per year)',
        'description' => 'An estimate of the number of participants per year.',
    ],
    'communities' => [
        'label'       => 'Communities',
        'instruction' => 'Please select all options that apply.',
        'description' => 'The list of target communities and sub-communities.',
    ],
    'departmental_results_framework_indicators' => [
        'label'       => 'Indicators',
        'instruction' => 'Please select all DRF indicators that apply to this project.',
        'description' => 'The Departmental Results Framework (DRF) indicators addressed by this project.',
    ],
    'cost_centre' => [
        'label'       => 'Cost Centre',
        'instruction' => 'Please provide your Cost Centre.',
        'description' => 'The project cost centre.',
        'hint'        => 'Ex: Q12345',
    ],
    'spendings' => [
        'internal_resource' => [
            'label'       => 'Internal Resources',
            'description' => 'The list of internal resources at the School that may be impacted by the project.',
            'help'        => 'For example: Learning Solutions resources may be required to create an online course or a video. New Faculty members may need to be hired to deliver an in-class course.',
        ],
        'cost_description' => [
            'label'       => 'Cost Description',
            'description' => 'The detailed description of the cost.',
            'help'        => 'For example: The amount reflects the salary of a new Faculty member. The amount is the cost associated to translating and editing a video.',
        ],
        'cost' => [
            'label'       => 'Cost',
            'description' => 'The approximate cost required.',
            'help'        => 'For example: $85,000 for an estimated salary of a subject matter expert. $4,000 for translation.',
        ],
        'recurrence' => [
            'label'       => 'Recurrence',
            'description' => 'The recurrence of the cost.',
            'help'        => 'For example: Is the cost an initial investment or will it be recurring annually?',
        ],
        'comments' => [
            'label'       => 'Comments',
            'instruction' => 'Please add any relevant information related to the cost or resource captured above.',
            'description' => 'Any other relevant information about this specific cost or resource.',
            'help'        => 'For example: Using two resources in the Learning Solutions team represents 40% of their current available workforce and may impact other School projects.',
        ],
    ],
    'other_operational_considerations' => [
        'label'       => 'Other Operational Considerations',
        'instruction' => 'Is there an additional impact on costs or resources?',
        'description' => 'Any other relevant operational consideration regarding costs or resources.',
    ],
    'risks' => [
        'risk_type' => [
            'label'       => 'Risk',
            'instruction' => 'Please indicate all risks for the School if this project does not go ahead.',
            'description' => 'The description of a known constraint, risk or issue affecting the project.',
        ],
        'risk_type_other' => [
            'label' => 'Other Risk',
        ],
        'risk_rationale' => [
            'label'       => 'Rationale',
            'instruction' => 'Please indicate why this is a risk.',
            'description' => 'The impact on the School or Government operations if this project is not realized.',
            'help'        => 'For example: ATIP officials would be unable to perform their duties based on outdated mandatory training content.',
        ],
    ],
    'comments' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the business case.',
    ],
];
