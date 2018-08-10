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
        'description' => 'The list of the government core curriculum priorities addressed by this project.',
    ],
    'request_sources' => [
        'label'       => 'Request Sources',
        'instruction' => 'Please select all options that apply. If needed, use the Others field to specify any missing options.',
        'description' => 'The sources of the request.',
        'help'        => '
            <ul>
                <li><span>OCHRO or PCO request</span>: Office of the Chief Human Resources Officer or Privy Council Office request</li>
            </ul>',
    ],
    'request_source_other' => [
        'label'       => 'Other Request Sources',
    ],
    'potential_solution_types' => [
        'label'       => 'Potential Solution Types',
        'instruction' => 'Please select all options that apply. If needed, use the Others field to specify any missing options.',
        'description' => 'The list of potential solution types to solve the business issue.',
        'help'        => '
            <ul>
                <li><span>Commercial Off-The-Shelf (COTS)</span>: Third party material that requires purchase. E.g.: Skillsoft.</li>
            </ul>',
    ],
    'potential_solution_type_other' => [
        'label'       => 'Other Potential Solution Types',
    ],
    'learning_response_strategy' => [
        'label'       => 'Learning Response Strategy',
        'instruction' => 'Please provide a high-level overview of your project and how it addresses the perfomance gaps or the business issues identified previously.',
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
        'instruction' => 'Please select the option that apply or use the Other field to specify another option.',
        'description' => 'The departmental benefit type.',
    ],
    'departmental_benefit_type_other' => [
        'label'       => 'Other Type',
    ],
    'departmental_benefit_rationale' => [
        'label'       => 'Rationale',
        'description' => 'The departmental benefit rationale.',
    ],
    'learners_benefit_type' => [
        'label'       => 'Type',
        'instruction' => 'Please select the option that apply or use the Other field to specify another option.',
        'description' => 'The learner benefit type.',
    ],
    'learner_benefit_type_other' => [
        'label'       => 'Other Type',
    ],
    'learners_benefit_rationale' => [
        'label'       => 'Rationale',
        'description' => 'The learner benefit rationale.',
    ],
    'cost_center' => [
        'label'       => 'Cost Center',
        'instruction' => 'Please provide your Cost Center.',
        'description' => 'The project cost center.',
        'hint'        => 'Ex: Q12345',
    ],
    'maintenance_fund' => [
        'label'       => 'Estimated Additional Operations and Maintenance (O&M) Funds Required',
        'description' => 'The estimated additional Operation and Maintenance funds required.',
        'help'        => 'Also known as "Non-salary related funds".',
    ],
    'maintenance_fund_rationale' => [
        'label'       => 'Additional Operations and Maintenance (O&M) Funds Rationale',
        'instruction' => 'Please provide a rationale for the additional Operations and Maintenance funds.',
        'description' => 'The additional Operations and Maintenance funds rationale.',
    ],
    'salary_fund' => [
        'label'       => 'Estimated Additional Salary Funds Required',
        'description' => 'The estimated additional Salary funds required.',
    ],
    'salary_fund_rationale' => [
        'label'       => 'Additional Salary Funds Rationale',
        'instruction' => 'Please provide a rationale for the Salary funds.',
        'description' => 'The additional Salary funds rationale.',
    ],
    'internal_resources' => [
        'label'       => 'Required Internal Resources',
        'instruction' => 'Please select all options that apply. If needed, use the Others field to specify any missing options.',
        'description' => 'A list of the departemental resources that will be required for this project.',
        'help'        => '
            <ul>
                <li><span>Faculty</span>: This team works on the onboarding and management of faculty members. E.g.: Instructors.</li>
                <li><span>A/V</span>: This team prepares rooms for course, events, etc.</li>
                <li><span>NOP</span>: This team helps planning national operations such as course offerings or events.</li>
            </ul>
        '
    ],
    'internal_resource_other' => [
        'label'       => 'Other Required Internal Resources',
    ],
    'risk_type' => [
        'label'       => 'Risk',
        'instruction' => 'Please select the option that apply or use the Other field to specify another option.',
        'description' => 'The description of a known constraint, risk or issue affecting the project.',
    ],
    'risk_type_other' => [
        'label'       => 'Other Risk',
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
        'description' => 'Any other relevant information about the business case.',
    ],
];
