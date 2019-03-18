<?php

return [
    'form_title' => 'Design',
    'tabs'  => [
        'description'     => 'Description',
        'specifications'  => 'Specifications',
        'content'         => 'Content',
        'classifications' => 'Classifications',
        'prerequisites'   => 'Prerequisites',
        'clients'         => 'Clients',
        'timeframe'       => 'Timeframe',
        'costs'           => 'Costs',
        'comments'        => 'Comments',
    ],
    'form_section_groups' => [
        'applicable_policy' => 'Applicable Policy|Applicable Policies',
        'additional_cost'   => 'Additional Cost|Additional Costs',
    ],
    'learning_product_code' => [
        'label'       => 'Learning Product Code',
        'description' => 'The learning product code.',
        'help'        => 'This was formerly known as the "legacy course code". All related or embedded learning products must share the same Learning Product Code. For example, all videos and job-aids embedded in a given course must share the same Learning Product Code.',
        'instruction' => 'For new learning products, please contact the LPA administrators to obtain a new code.',
    ],
    'sequence' => [
        'label'       => 'Sequence',
        'description' => 'The learning product sequence number within the Learning Product Code.',
        'help'        => 'For example, each video within the product group P999 will be assigned a unique sequence number (1, 2, 3 ...)',
        'instruction' => 'Please assign a unique sequence number for each learning product sharing the same Learning Product Code.',
    ],
    'version' => [
        'label'       => 'Version',
        'description' => 'The learning product version.',
    ],
    'description' => [
        'label'       => 'Description',
        'description' => 'The learning product description.',
        'help'        => 'This is the internal description of the learning product. It will serve as input for the creation of the official description in ILMS and on GCcampus.',
    ],
    'learning_objectives' => [
        'label'       => 'Learning objectives',
        'description' => 'The learning objectives of this product.',
        'instruction' => 'Please identify the learning objectives of the learning product.',
    ],
    'target_audience_description' => [
        'label'       => 'Target Audience Description',
        'description' => 'The description of the learning product target audience.',
        'help'        => 'This section will help the Client Centre to guide learners in their learning product choices.',
    ],
    'outcome_types' => [
        'label'       => 'Learning Outcome',
        'description' => 'The learning outcome types.',
        'instruction' => 'Please identify the learning outcome types of the learning product.',
    ],
    'is_required_training' => [
        'label'       => 'Required Training / Part of a Certification',
        'description' => 'Whether or not the learning product is considered as "required training" or "part of a certification".',
        'help'        => 'The definition of “required training” can be found in the Treasury Board Policy on Learning, Training, and Development.',
        'instruction' => 'Please indicate whether the Learning Product is considered "required training" and/or "part of a certification"?',
    ],
    'total_duration' => [
        'label'       => 'Total Duration',
        'description' => 'The learning product\'s total duration, in hours.',
        'help'        => 'For example:  1 day = 7.5 hours.',
        'instruction' => 'Please enter the total duration of the learning product, in hours.',
    ],
    'possible_offering_types' => [
        'label'       => 'Possible Offering Types',
        'description' => 'The possible offering types.',
        'help'        => '
            <ul>
                <li><span>In-person</span>: All participants must physically attend from one of the course/event official locations.</li>
                <li><span>Virtual</span>: All participants must remotely attend using a bi-directional communication platform such as WebEx.</li>
                <li><span>Simultaneous Virtual and In-Person</span>: Some participants may attend in-person while some others may attend virtually.</li>
            </ul>
        ',
        'instruction' => 'Please identify the possible offering types for this learning product.',
    ],
    'registration_mode' => [
        'label'       => 'Registration Mode',
        'description' => 'How registration is offered to participants.',
        'help'        => '
            <ul>
                <li><span>Elective</span>: Participants can enrol directly through GCcampus.</li>
                <li><span>Allocated</span>: Participants can only be enrolled by their training coordinator.</li>
            </ul>
        ',
        'instruction' => 'Please identify the registration mode of the learning product.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Expected number of participants (per year)',
        'description' => 'An estimate of the number of participants per year.',
    ],
    'applicable_policies' => [
        'applicable_policy' => [
            'label'       => 'Applicable Policies',
            'description' => 'List of policies related to the learning product.',
            'instruction' => 'Please provide the list of the policies influencing the content of the learning product.',
        ]
    ],
    'content_source_type' => [
        'label'       => 'Content Source',
        'description' => 'The learning product content source.',
        'help'        => '
            <ul>
                <li><span>New</span>: Did not exist before.</li>
                <li><span>Replacement</span>: Pulled from one active or archived learning product only.</li>
                <li><span>Split</span>: Pulled from one active or archived learning product into two or more new learning products including this one.</li>
                <li><span>Merged</span>: Pulled from two or more active or archived learning products into this learning product.</li>
                <li><span>Refactor</span>: Pulled from two or more active or archived learning products into two of more learning products including this one.</li>
            </ul>
        ',
        'instruction' => 'Select the situation that best represents the learning product content.',
    ],
    'content_source_codes' => [
        'label'       => 'Original Products',
        'description' => 'The list of active or archived learning product codes that contributed to the current learning product content.',
        'instruction' => 'Please enter a list of learning product codes.',
    ],
    'topics' => [
        'label'       => 'Topics',
        'description' => 'The list of topics addressed by this learning product.',
        'help'        => 'Also known as curriculum streams.',
        'instruction' => 'Please select all options that apply.',
    ],
    'programs' => [
        'label'       => 'Programs',
        'description' => 'The list of corresponding learning programs.',
    ],
    'series' => [
        'label'       => 'Series',
        'description' => 'The list of corresponding learning series.',
    ],
    'curriculum_type' => [
        'label'       => 'Curriculum Type',
        'description' => 'The learning product curriculum type.',
    ],
    'management_accountability_framework_areas' => [
        'label'       => 'Management Accountability Framework Sections Addressed',
        'description' => 'The Management Accountability Framework (MAF) sections addressed by this learning product.',
    ],
    'competencies' => [
        'label'       => 'Competencies Addressed',
        'description' => 'The list of competencies addressed through this learning product.',
        'help'        => 'Also known as the performance gap.',
        'instruction' => 'Please select a maximum of 3 options.',
    ],
    'target_audience_knowledge_level' => [
        'label'       => 'Learner Knowledge Level',
        'description' => 'A description of the target audience\'s knowledge level.',
        'instruction' => 'Please identify the learner\'s level of knowledge.',
    ],
    'communities' => [
        'label'       => 'Communities',
        'description' => 'The list of communities and sub-communities targeted by this learning product.',
        'instruction' => 'Please identify the learner\'s level of knowledge.',
    ],
    'mandatory_prerequisites' => [
        'label'       => 'Mandatory Pre-Requisite Learning Products',
        'description' => 'The list of mandatory pre-requisite learning product codes.',
        'instruction' => 'Please enter the list of learning product codes.',
    ],
    'recommended_prerequisites' => [
        'label'       => 'Recommended Pre-Requisite Learning Products',
        'description' => 'The list of recommended pre-requisite learning product codes.',
        'instruction' => 'Please enter the list of learning product codes.',
    ],
    'complementary_learning_products' => [
        'label'       => 'Complementary Learning Products',
        'description' => 'The list of complementary learning product codes.',
        'instruction' => 'Please enter the list of learning product codes.',
    ],
    'prescribed_by_tbs' => [
        'label'       => 'Prescribed by Treasury Board',
        'description' => 'The description of which authoritative body prescribes this learning product.',
        'help'        => '
            <ul>
                <li><span>Treasury Board of Canada Secretariat</span>: Also known as “Required for all departments”.</li>
                <li><span>One or more departments</span>:  Also known as “Mandatory for specific departments”.</li>
            </ul>
        ',
    ],
    'prescribed_by_departments' => [
        'label'       => 'Prescriptor Departments',
        'description' => 'The list of all the Government of Canada departments, agencies, Crown corporations and special operating agencies that prescribe this learning product.',
    ],
    'recommended_by_departments' => [
        'label'       => 'Recommended By Departments',
        'description' => 'The list of all the Government of Canada departments, agencies, Crown corporations and special operating agencies that recommend this learning product.',
    ],
    'expected_pilot_start_date' => [
        'label'       => 'Expected Pilot Start Date',
        'description' => 'The expected pilot start date.',
        'instruction' => 'What is the expected start date of the pilot of the learning product?',
    ],
    'expected_launch_date' => [
        'label'       => 'Expected Launch Date',
        'description' => 'The expected launch date.',
    ],
    'recommended_review_interval' => [
        'label'       => 'Recommended Review Interval (in months)',
        'description' => 'The recomended number of months between each systematic review of this learning product content.',
    ],
    'additional_costs' => [
        'rationale' => [
            'label'       => 'Rationale',
            'description' => 'The rationale for the additional costs, i.e. costs outside of the project’s established budget.',
            'help'        => 'For example: The video translation will have to be outsourced to respect the launch schedule.',
            'instruction' => 'Why does this learning product require additional funding (outside of the project’s established budget)?',
        ],
        'costs' => [
            'label'       => 'Costs',
            'description' => 'The approximate additional costs required to develop the learning product.',
        ],
    ],
    'internal_order' => [
        'label'       => 'Internal Order',
        'description' => 'The Internal Order number.',
        'hint'        => 'E.g.: M123456',
    ],
    'comments' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the learning product.',
    ],
];
