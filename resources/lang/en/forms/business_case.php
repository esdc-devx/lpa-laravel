<?php

return [
    'title' => 'Business Case',
    'tabs' => [
        'business_drivers'      => 'Business Drivers',
        'proposal'              => 'Proposal',
        'timeframe'             => 'Timeframe',
        'audience'              => 'Audience',
        'departmental_benefit'  => 'Departmental Benefit|Departmental Benefits',
        'learners_benefit'      => 'Learners Benefit|Learners Benefits',
        'costs'                 => 'Costs',
        'internal_resources'    => 'Internal Resources',
        'risk'                  => 'Risk|Risks',
        'comment'               => 'Comments',
    ],
    'business_issue' => [
        'label'       => 'Business Issues',
        'instruction' => 'Please describe the performance gaps or business issues addressed by this project.',
        'description' => 'The business issues motivating this project.',
        'help'        => 'What are the business issues, problems or performance gaps that the School is trying to solve?',
    ],
    'government_priorities' => [
        'label'       => 'Government Core Curriculum Priorities',
        'instruction' => 'Please select all options that apply.',
        'description' => 'The list of the Government Core Curriculum Priorities addressed by this project.',
    ],
    'request_sources' => [
        'label'       => 'Request Source',
        'description' => 'The source of the request.',
    ],
    'potential_solution_types' => [
        'label'       => 'Potential Solution Types',
        'instruction' => 'Please select all options that apply.',
        'description' => 'The list of potential solution types to solve the Business issue.',
        'help'        => '
            <ul>
                <li><span>COTS</span>: Third party material that requires purchase. E.g.: Skillsoft.</li>
            </ul>',
    ],
    'learning_response_strategy' => [
        'label'       => 'Learning Response Strategy (LRS)',
        'instruction' => 'Please provide a high-level overview of your project and how it addresses the gaps and issues described above.',
        'description' => 'The proposed Learning Response Strategy (LRS) description.',
    ],
    'is_required_training' => [
        'label'       => 'Required Training / Part of Certification',
        'instruction' => 'Please indicate whether the Learning Products included in this project are considered as "required training" (under any policy) and/or part of a certification?',
        'description' => 'An indicator of whether or not the Learning Products included in the project are considered as "required training" or "part of a certification."',
    ],
    'timeframe' => [
        'label'       => 'Anticipated Timeframe',
        'description' => 'The project\'s anticipated timeframe.',
        'help'        => 'Targeted timeframe until the launch of last included products.',
    ],
    'timeframe_rationale' => [
        'label'       => 'Anticipated Timeframe Rationale',
        'instruction' => 'Please explain the above anticipated timeframe.',
        'description' => 'The rationale for the project\'s anticipated timeframe.',
    ],
    'communities' => [
        'label'       => 'Communities',
        'instruction' => 'Please select all options that apply.',
        'description' => 'The list of target communities and sub-communities.',
    ],
    'expected_annual_participant_number' => [
        'label'       => 'Expected number of participants (per year)',
        'description' => 'An estimate of the number of participants per year.',
    ],
    'departmental_benefit_type' => [
        'label'       => 'Type',
        'description' => 'The Departmental Benefit type.',
    ],
    'departmental_benefit_rationale' => [
        'label'       => 'Rationale',
        'description' => 'The Departmental Benefit rationale.',
    ],
    'learners_benefit_type' => [
        'label'       => 'Type',
        'description' => 'The Learner Benefit type.',
    ],
    'learners_benefit_rationale' => [
        'label'       => 'Rationale',
        'description' => 'The Learner Benefit rationale.',
    ],
    'cost_center' => [
        'label'       => 'Cost Center',
        'instruction' => 'Please provide your Cost Centre.',
        'description' => 'The project cost centre.',
        'hint'        => 'Ex: Q12345',
    ],
    'maintenance_fund' => [
        'label'       => 'Estimated Additional Operations and Maintenance (O&M) Funds Required',
        'description' => 'The estimated additional Operation and Maintenance funds required.',
        'help'        => 'Also known as "Non-salary related funds".',
    ],
    'maintenance_fund_rationale' => [
        'label'       => 'Additional Operations and Maintenance (O&M) Funds Rationale',
        'instruction' => 'Please provide a description and rationale for the additional Operations and Maintenance funds.',
        'description' => 'The additional Operations and Maintenance funds description and rationale.',
    ],
    'salary_fund' => [
        'label'       => 'Estimated Additional Salary Funds Required',
        'description' => 'The estimated additional Salary funds required.',
    ],
    'salary_fund_rationale' => [
        'label'       => 'Additional Salary Funds Rationale',
        'instruction' => 'Please provide a description and rationale for the Salary funds.',
        'description' => 'The additional Salary funds description and rationale.',
    ],
    'internal_resources' => [
        'label'       => 'Required Internal Resources',
        'instruction' => 'Please select all options that apply.',
        'description' => 'A list of the departemental resources that will be required for this project.',
        'help'        => '
            <ul>
                <li><span>Faculty</span>: This team works on the onboarding and management of faculty members. E.g.: Instructors.</li>
                <li><span>A/V</span>: This team prepares rooms for course, events, etc.</li>
                <li><span>NOP</span>: This team helps planning national operations such as course offerings or events.</li>
            </ul>
        '
    ],
    'risks' => [
        'label'       => 'Risk',
        'description' => 'A description of a known constraint, risk or issue affecting the project.',
    ],
    'risk_impact_level' => [
        'label'       => 'Impact',
        'description' => 'An assessment of the impact on the project if the risk ever materializes.',
    ],
    'risk_probability_level' => [
        'label'       => 'Probability',
        'description' => 'An assessment of the likelihood or probability that the risk will materialize.',
    ],
    'risk_rationale' => [
        'label'       => 'Rationale',
        'instruction' => 'Please indicate why this is a risk.',
        'description' => 'A description of the risk rationale.',
    ],
    'comment' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the buisness case.',
    ],
];
