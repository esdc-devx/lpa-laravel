<?php

return [
    'type'    => [
        'success'         => 'Success',
        'info'            => 'Info',
        'warning'         => 'Warning',
        'error'           => 'Error',
    ],
    'title'   => [
        'delete_project'  => 'Delete Project',
        'pending_changes' => 'Pending Changes',
        'submit_form'     => 'Submit Form',
        'cancel_process'  => 'Cancel Process',
    ],
    'message' => [
        'created'                 => '<b>:name</b> has been created.',
        'updated'                 => '<b>:name</b> has been updated.',
        'project_updated'         => 'Project updated.',
        'user_updated'            => 'User profile has been updated.',
        'deleted'                 => '<b>:name</b> has been deleted.',
        'project_deleted'         => 'Project deleted.',
        'process_started'         => 'The <b>:name</b> process is started.',
        'process_cancelled'       => 'The process has been cancelled.',
        'changes_saved'           => 'The changes have been saved.',
        'changes_discarded'       => 'The changes have been discarded.',
        'form_submitted'          => 'The form has been submitted.',
        'submit_form'             => 'Do you want to submit the form?',
        'language_toggle'         => 'Changing the language will reset the table filters. Do you want to continue?',
        'delete_project'          => 'You are about to delete the project named "<b>:name</b>" (<b>LPA #: :id</b>) and all its related information. Do you want to continue?',
        'already_deleted_project' => 'The deletion failed because the project has already been deleted.',
        'start_process'           => 'Do you want to start the <b>:process_name</b> process for the current project?',
        'cancel_process'          => 'Cancelling a process is a critical and definitive action. Do you really want to proceed with the cancellation of this process?',
        'pending_changes'         => 'Changes have not been saved yet. Do you want to discard those changes?',
        'validation_failure'      => 'Validation failure.  Please correct all errors (:num) before re-submitting the form.',
        'cancel_project'          => 'Cancelling a project is a critical and definitive action. This action will automatically take place upon submitting this form with this option.',
    ]
];
