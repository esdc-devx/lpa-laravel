<?php

return [
    'form_title' => 'Détails opérationnels',
    'tabs'  => [
        'instructors'      => 'Instructeurs',
        'guest_speakers'   => 'Conférenciers invités',
        'offering_details' => 'Détails de l\'offre',
        'rooms'            => 'Salles',
        'materials'        => 'Matériels',
        'documents'        => 'Documents',
        'gc_campus'        => 'GCcampus',
        'comments'         => 'Commentaires',
    ],
    'form_section_groups' => [
        'instructor'    => 'Instructeur|Instructeurs',
        'guest_speaker' => 'Conférencier invité|Conférenciers invités',
        'room'          => 'Salle|Salles',
        'material'      => 'Matériel|Matériels',
        'document'      => 'Document|Documents',
    ],
    'instructors' => [
        'required_profile' => [
            'label'       => 'Profil requis',
            'description' => 'La description des exigences relatives au profil de l’instructeur.',
            'instruction' => 'Veuillez fournir une description des qualifications, des compétences et des qualités personnelles requises de l\'instructeur du cours.',
        ],
        'schedule' => [
            'label'       => 'Calendrier',
            'description' => 'La description du calendrier de l’instructeur.',
            'hint'        => 'P. ex. : "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'guest_speakers' => [
        'required_profile' => [
            'label'       => 'Profil requis',
            'description' => 'La description du profil requis pour le conférencier (également connu sous le nom d\'expert en la matière).',
        ],
        'schedule' => [
            'label'       => 'Calendrier',
            'description' => 'La description en anglais du calendrier du conférencier (également connu sous le nom d\'expert en la matière). ',
            'hint'        => 'P. ex. : "Day 2 only", "Day 1 and 3", "Day 1 PM only".',
        ],
    ],
    'number_of_virtual_producers_per_offering' => [
        'label'       => 'Nombre de producteurs virtuels par offre',
        'description' => 'Le nombre de producteurs virtuels requis par offre.',
    ],
    'minimum_number_of_participant_per_offering' => [
        'label'       => 'Nombre minimum de participants par offre',
        'description' => 'Le nombre minimum de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'maximum_number_of_participant_per_offering' => [
        'label'       => 'Nombre maximum de participants par offre',
        'description' => 'Le nombre maximum de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'optimal_number_of_participant_per_offering' => [
        'label'       => 'Nombre optimal de participants par offre',
        'description' => 'Le nombre optimal de participants par offre pour offir ce produit d\'apprentissage avec succès.',
    ],
    'waiting_list_maximum_size' => [
        'label'       => 'Nombre maximum de participants par liste d\'attente',
        'description' => 'Le nombre maximum de participants permis sur la liste d\'attente par offre.',
    ],
    'session_template' => [
        'label'       => 'Modèle de session',
        'description' => 'Le modèle d\'horaire pour les offres du produit d\'apprentissage.',
        'help'        => 'Par exemple: "Ce cours s\'offre en cinq séances de 2 heures, à raison d\'une séance par semaine."'
    ],
    'external_delivery' => [
        'label'       => 'Offre externe',
        'description' => 'L\'indicateur indiquant si ce produit d\'apprentissage peut être donné à l’extérieur de l\'EFPC ou non.',
    ],
    'rooms' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre de salles de ce type requises par offre.',
        ],
        'room_usage' => [
            'label'       => 'Usage',
            'description' => 'L\'usage de cette salle dans ce produit d\'apprentissage.',
        ],
        'room_type' => [
            'label'       => 'Type',
            'description' => 'Le type de salle requis.',
        ],
        'room_layout' => [
            'label'       => 'Aménagmeent',
            'description' => 'L\'aménagement de la salle.',
            'help'        => '
                <ul>
                    <li><span>Groupes de tables</span> :  Aussi appelé "des îlots".</li>
                    <li><span>Rangées - chaises uniquement</span> : Aussi apellé "de style amphithéâtre".</li>
                </ul>
            ',
        ],
        'room_layout_other' => [
            'label'       => 'Autre aménagement',
            'description' => 'La description d\'une autre configuration particulière des tables de la salle principale.',
        ],
        'special_requirement_description' => [
            'label'       => 'Exigences particulières',
            'description' => 'La description de toute exigence particulière pour les salles principales.',
        ],
    ],
    'materials' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre d’éléments requis, par dénominateur indiqué.',
        ],
        'material_item' => [
            'label'       => 'Élément',
            'description' => 'La description de l\'élément requis.',
        ],
        'material_item_other' => [
            'label'       => 'Autre élément',
            'description' => 'La description d\'un autre élément requis.',
        ],
        'material_denominator' => [
            'label'       => 'Par',
            'description' => 'Le dénominateur de quantité.',
        ],
        'material_location' => [
            'label'       => 'Où',
            'description' => 'L’endroit où l’élément doit se trouver, doit être installé ou doit être disponible.',
        ],
        'reusable' => [
            'label'       => 'Réutilisable',
            'description' => 'L\'indicateur indiquant si le matériel est réutilisable pour plus d\'une offre.',
        ],
        'notes' => [
            'label'       => 'Remarques',
            'description' => 'La description de tout autre renseignement pertinent relatif à cet item.',
            'help'        => 'P. ex. : "Markers must be red and blue", "Software XYZ version 2.3 must be pre-installed on student computer".',
        ],
    ],
    'documents' => [
        'quantity' => [
            'label'       => 'Quantité',
            'description' => 'Le nombre de copies papier requis, selon le dénominateur indiqué.',
        ],
        'document_category' => [
            'label'       => 'Catégorie',
            'description' => 'La catégorie générale du document requis.',
        ],
        'document_category_other' => [
            'label'       => 'Autre Catégorie',
            'description' => 'La description d\'une autre catégorie générale du document requis.',
        ],
        'document_denominator' => [
            'label'       => 'Par',
            'description' => 'Le dénominateur de quantité.',
        ],
        'title' => [
            'label'       => 'Titre',
            'description' => 'Le titre officiel du document.',
        ],
        'version' => [
            'label'       => 'Version',
            'description' => 'La version requise du document.',
        ],
        'link' => [
            'label'       => 'Lien',
            'description' => 'Le localisateur de ressources uniforme (adresse URL) du document.',
            'instruction' => 'Saisir l’adresse URL valide ou indiquer qu’il s’agit d’une copie papier s’il n’existe aucune version électronique.',
            'hint'        => 'P. ex. : http://gcdocs.csps-efpc.gc.ca/otcs/llisapi.dll?func=ll&objId=10205305&objAction=browse&viewType=1',
        ],
        'printing_specifications' => [
            'label'       => 'Caractéristiques d’impression',
            'description' => 'La description de toute caractéristique d’impression du document.',
            'help'        => 'Pensez aux éléments suivants : couleur, taille, finition du papier, recto ou recto verso, agrafes, perforation, etc.',
        ],
        'reusable' => [
            'label'       => 'Réutilisable',
            'description' => 'Un indicateur indiquant si le document est réutilisable pour plus d\'une offre.',
        ],
        'notes' => [
            'label'       => 'Remarques',
            'description' => 'La description de tout autre renseignement pertinent relatif à ce document.',
        ],
    ],
    'should_be_published' => [
        'label'       => 'Devrait être publié sur GCcampus',
        'description' => 'Un indicateur pour préciser si le produit d’apprentissage devrait être publié sur GCcampus.',
    ],
    'note_content' => [
        'label'       => 'Contenu de la note dans GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) de la note du produit d’apprentissage à publier sur GCcampus, le cas échéant.',
    ],
    'disclaimer_content' => [
        'label'       => 'Contenu de l’avertissement dans GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) de l’avertissement concernant le produit d’apprentissage à afficher sur GCcampus, le cas échéant.',
    ],
    'summary_content' => [
        'label'       => 'Contenu du résumé pour GCcampus',
        'description' => 'Le contenu brut (en anglais ou en français) du résumé du produit d’apprentissage à afficher sur GCcampus, le cas échéant.',
    ],
    'comments' => [
        'label'       => 'Commentaires généraux',
        'description' => 'Tout autre renseignement pertinent relatif aux détails opérationnels.',
    ],
];
