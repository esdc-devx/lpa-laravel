<?php

return [
    'form_title' => 'Operational Details',
    'tabs'  => [
        'instructors'      => 'Instructors',
        'guest_speakers'   => 'Guest Speakers',
        'offering_details' => 'Offering Details',
        'rooms'            => 'Rooms',
        'materials'        => 'Materials',
        'documents'        => 'Documents',
        'gc_campus'        => 'GCcampus',
        'comments'         => 'Comments',
    ],
    'form_section_groups' => [
        'instructor'    => 'Instructor|Instructors',
        'guest_speaker' => 'Guest Speaker|Guest Speakers',
        'room'          => 'Room|Rooms',
        'material'      => 'Material Item|Material Items',
        'document'      => 'Document|Documents',
    ],
    'instructors' => [
        'required_profile' => [
            'label'       => 'Required Profile',
            'description' => 'The description of the instructor profile requirements.',
            'instruction' => 'Please provide a description of the required qualifications, competencies and personal suitability of the course instructor.',
        ],
        'schedule' => [
            'label'       => 'Schedule',
            'description' => 'The description of the instructor schedule.',
            'hint'        => 'E.g. : "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'guest_speakers' => [
        'required_profile' => [
            'label'       => 'Required Profile',
            'description' => 'The description of the required profile for the guest speaker (also known as the subject matter expert).',
        ],
        'schedule' => [
            'label'       => 'Schedule',
            'description' => 'The description of the schedule for guest speaker (also known as the subject matter expert).',
            'hint'        => 'E.g. : "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'number_of_virtual_producers_per_offering' => [
        'label'       => 'Number of Virtual Producers per Offering',
        'description' => 'The number of virtual producers required per offering.',
    ],
    'minimum_number_of_participant_per_offering' => [
        'label'       => 'Minimum Number of Participants per Offering',
        'description' => 'The minimum number of participants per offering to successfully deliver this learning product.',
    ],
    'maximum_number_of_participant_per_offering' => [
        'label'       => 'Maximum Number of Participants per Offering',
        'description' => 'The maximum number of participants per offering to successfully deliver this learning product.',
    ],
    'optimal_number_of_participant_per_offering' => [
        'label'       => 'Optimal Number of Participants per Offering',
        'description' => 'The optimal number of participants per offering to successfully deliver this learning product.',
    ],
    'waiting_list_maximum_size' => [
        'label'       => 'Maximum Number of Participants per Waiting list',
        'description' => 'The maximum number of participants allowed on the offerings\' waiting list.',
    ],
    'session_template' => [
        'label'       => 'Session template',
        'description' => 'The schedule template for the learning product offerings.',
        'help'        => 'For example: "This course is offered in five sessions of 2 hours, one session per week."'
    ],
    'external_delivery' => [
        'label'       => 'External Delivery',
        'description' => 'The indicator of whether or not this learning product can be delivered outside CSPS.',
    ],
    'rooms' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of rooms of this kind required per offering.',
        ],
        'room_usage' => [
            'label'       => 'Usage',
            'description' => 'The usage of this room in the learning product.',
        ],
        'room_type' => [
            'label'       => 'Type',
            'description' => 'The type of room needed.',
        ],
        'room_layout' => [
            'label'       => 'Layout',
            'description' => 'The room layout.',
            'help'        => '
                <ul>
                    <li><span>Table Groups</span>: Also known as "Islands".</li>
                    <li><span>Rows - Chairs only</span>:  Also known as "Theatre Style".</li>
                </ul>
            ',
        ],
        'room_layout_other' => [
            'label'       => 'Other Layout',
            'description' => 'The description of another specific table layout for the main room.',
        ],
        'special_requirement_description' => [
            'label'       => 'Special Requirements',
            'description' => 'The description of any special requirement for the main rooms.',
        ],
    ],
    'materials' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of items required per the indicated denominator.',
        ],
        'material_item' => [
            'label'       => 'Item',
            'description' => 'The description of the item required.',
        ],
        'material_item_other' => [
            'label'       => 'Other Item',
            'description' => 'The description of another item required.',
        ],
        'material_denominator' => [
            'label'       => 'Per/By',
            'description' => 'The quantity denominator.',
        ],
        'material_location' => [
            'label'       => 'Where',
            'description' => 'The location where the item must be located, installed or available.',
        ],
        'reusable' => [
            'label'       => 'Reusable',
            'description' => 'The indicator of whether the item is reusable for more than one offering.',
        ],
        'notes' => [
            'label'       => 'Notes',
            'description' => 'The description of any other relevant information about this item.',
            'help'        => 'E.g.: "Markers must be red and blue", "Software XYZ version 2.3 must be pre-installed on student computer".',
        ],
    ],
    'documents' => [
        'quantity' => [
            'label'       => 'Quantity',
            'description' => 'The number of items required per the indicated denominator.',
        ],
        'document_category' => [
            'label'       => 'Category',
            'description' => 'The general category of the document required.',
        ],
        'document_category_other' => [
            'label'       => 'Other Category',
            'description' => 'The description of another general category of the document required.',
        ],
        'document_denominator' => [
            'label'       => 'Per/By',
            'description' => 'The quantity denominator.',
        ],
        'title' => [
            'label'       => 'Title',
            'description' => 'The official title of the document.',
        ],
        'version' => [
            'label'       => 'Version',
            'description' => 'The required version of the document.',
        ],
        'link' => [
            'label'       => 'Link',
            'description' => 'The Uniform Resource Locator (URL) of the document.',
            'instruction' => 'Enter a valid Uniform Resource Locator (URL) or "Hard Copy" if no electronic copy exists.',
            'hint'        => 'E.g. : http://gcdocs.csps-efpc.gc.ca/otcs/llisapi.dll?func=ll&objId=10205305&objAction=browse&viewType=1',
        ],
        'printing_specifications' => [
            'label'       => 'Printing Specifications',
            'description' => 'The description of any printing specifications for the document.',
            'help'        => 'Think about colour, size, paper finish, single/double-sided, staples, hole-punched, etc.',
        ],
        'reusable' => [
            'label'       => 'Reusable',
            'description' => 'An indicator of whether the document is reusable for more than one offering.',
        ],
        'notes' => [
            'label'       => 'Notes',
            'description' => 'The description of any other relevant information about the document.',
        ],
    ],
    'should_be_published' => [
        'label'       => 'Should be Published on Gccampus',
        'description' => 'An indicator of whether of not the learning product should be published on GCcampus.',
    ],
    'note_content' => [
        'label'       => 'GCcampus Note Content',
        'description' => 'The raw content (in English or French) of the Learning Product Note to display on Gccampus, if applicable.',
    ],
    'disclaimer_content' => [
        'label'       => 'Gccampus Disclaimer Content',
        'description' => 'The raw content (in English or French) of the Learning Product Disclaimer to display on GCcampus, if applicable.',
    ],
    'summary_content' => [
        'label'       => 'Gccampus Summary Content',
        'description' => 'The raw content (in English or French) of the Learning Product Summary to display on GCcampus, if applicable.',
    ],
    'comments' => [
        'label'       => 'General Comments',
        'description' => 'Any other relevant information about the learning product.',
    ],
];
