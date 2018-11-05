<?php

return [
    'title' => 'Planned Product List',
    'tabs'  => [
        'planned_product' => 'Planned Product|Planned Products',
        'comments'        => 'Comments',
    ],
    'form_section_groups' => [
        'planned_product' => 'Planned Product|Planned Products',
    ],
    'type' => [
        'label'       => 'Learning Product Type',
        'description' => 'The planned learning product type.',
        'help'        => '
            <ul>
                <li><span>Instructor-led Course</span>: In classroom, virtual or simultaneous, in which an instructor teaches a course in real time.</li>
                <li><span>Distance Learning Course</span>: Learners log into an online learning environment to access course content on their own time. Typically, courses are led by an instructor and delivered over a specific period of time.</li>
                <li><span>Online Self-Paced Course</span>: Participants access learning activities online. Learners complete their training at their own pace and do not interact with an instructor or other learners.</li>
            </ul>
        ',
    ],
    'quantity' => [
        'label'       => 'Quantity',
        'description' => 'The number of planned learning products of the given type.',
    ],
    'comments' => [
        'label'       => 'General Comments',
        'instruction' => 'If this project includes other product types such as videos, job aids, events, etc, please list below.',
        'description' => 'The detailed breakdown of all other planned learning products for this project.',
        'help'        => 'For example: 2 stand-alone videos; 1 event; 3 job aids',
    ],
];
