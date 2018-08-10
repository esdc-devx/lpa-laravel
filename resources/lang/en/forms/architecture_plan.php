<?php

return [
    'title' => 'Architecture Plan',
    'tabs' => [
        'planned_product'  => 'Planned Product|Planned Products',
        'comments'         => 'Comments',
    ],
    'type' => [
        'label'       => 'Learning Product Type',
        'description' => 'The planned Learning product type.',
        'help'        => '
            <ul>
                <li><span>Course / Instructor-led</span>: In classroom, virtual or simultaneous, in which an instructor teaches a course in real time.</li>
                <li><span>Course / Distance Learning</span>: Learners log into an online learning environment (e.g., Moodle, I-LMS Community, GCconnex) to access course content on their own time. Distance learning provides a learning space where learners and the instructor can interact in chat rooms. Typically, courses are led by an instructor and delivered over a specific period of time (e.g. two weeks) and learners are part of a defined cohort.</li>
                <li><span>Course / Online Self-Paced</span>: Participants access learning activities online. Learners complete their training at their own pace and do not interact with an instructor or other learners.</li>
            </ul>
        ',
    ],
    'quantity' => [
        'label'       => 'Quantity',
        'description' => 'The number of planned learning products of the given type.',
    ],
    'comment' => [
        'label'       => 'General Comments',
        'instruction' => 'If this project includes other product types such as videos, job aids, events, etc, please give their respective number.',
        'description' => 'Any other relevant information about the Architecture Plan.',
    ],
];
