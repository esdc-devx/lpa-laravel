<?php

return [
    // @refactor: Maybe regroup errors under specific themes (i.e. http, general, etc.).
    'general'                 => 'General exception. Please contact your administrator.',
    'not_found'               => 'Page Not Found',
    'forbidden'               => 'Insufficient privileges',
    'bad_request'             => 'Bad request. Please refresh your page.',
    'server_error'            => 'Server error. Please refresh your page.',
    'insufficient_privileges' => 'You no longer have the necessary authorizations to perform this operation. Contact your LPA administrator for more details.',
    'operation_denied'        => 'The requested operation is no longer possible.',
    'timeout'                 => 'Connection with the server timed out.',
    'error_edit_admin'        => 'Cannot edit admin account.',
    'csrf_not_found'          => 'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token',
    'process_instance_form'   => [
        'already_claimed'             => 'The form is currently being edited by another user.',
        'invalid_state'               => 'The form cannot be edited given its current status.',
        'invalid_organizational_unit' => 'The form can only be edited by a member of the Assigned Organizational Unit.',
        'invalid_process_state'       => 'The form cannot be edited since the process is no longer running.',
        'unclaim'                     => 'You no longer are the editor for this form.',
    ],
];
