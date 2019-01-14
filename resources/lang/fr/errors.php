<?php

return [
    'general'                 => 'Execption générale. Contactez votre administrateur.',
    'not_found'               => 'Page non-trouvée',
    'forbidden'               => 'Autorisations insuffisantes',
    'bad_request'             => 'Mauvaise requête. Rafraichissez votre page.',
    'server_error'            => 'Erreur serveur. Rafraichissez votre page.',
    'insufficient_privileges' => 'Vous ne disposez plus des autorisations nécessaires pour effectuer cette opération. Communiquez avec votre administrateur de l\'APA pour plus de détails.',
    'operation_denied'        => 'L\'opération demandée n\'est plus possible.',
    'timeout'                 => 'La connection avec le serveur a expiré.',
    'error_edit_admin'        => 'Impossible d\'éditer le compte administrateur.',
    'csrf_not_found'          => 'Token CSRF introuvable: https://laravel.com/docs/csrf#csrf-x-csrf-token',
    'process_instance_form'   => [
        'already_claimed'             => 'Le formulaire est en cours d\'édition par un autre utilisateur.',
        'invalid_state'               => 'Le formulaire ne peut pas être modifié étant donné son état actuel.',
        'invalid_organizational_unit' => 'Le formulaire ne peut être modifié que par un membre de l\'unité organisationnelle assignée.',
        'invalid_process_state'       => 'Le formulaire ne peut pas être modifié car le processus n\'est plus en cours.',
        'unclaim'                     => 'Vous n\'êtes plus l\'éditeur de ce formulaire.',
    ],
];
