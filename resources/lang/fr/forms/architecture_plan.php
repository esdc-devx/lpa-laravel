<?php

return [
    'tabs' => [
        'planned_product' => 'Produits prévus',
        'comments'        => 'Commentaires',
    ],
    'form_section_groups' => [
        'planned_product' => 'Produit prévu|Produits prévus',
    ],
    'type' => [
        'label'       => 'Type de produit d’apprentissage',
        'description' => 'Le type de produit d’apprentissage prévu.',
        'help'        => '
            <ul>
                <li><span>Apprentissage avec instructeur</span> : Formation en salle de classe, virtuelle, ou simultané, avec un instructeur qui donne un cours en direct.</li>
                <li><span>Apprentissage à distance</span> : Les apprenants se connectent à un environnement d’apprentissage en ligne pour accéder au contenu des cours au moment qui leur convient. En général, les cours sont dirigés par un instructeur et donnés sur une période de temps précise.</li>
                <li><span>Cours en ligne – à rythme libre</span> : Les participants accèdent aux activités d’apprentissage en ligne. Ils mènent leur formation à leur propre rythme, sans interactions avec un instructeur ou d’autres apprenants.</li>
            </ul>
        ',
    ],
    'quantity' => [
        'label'       => 'Quantité',
        'description' => 'Le nombre de produits d’apprentissage prévus de ce type.',
    ],
    'comments' => [
        'label'       => 'Commentaires généraux',
        'instruction' => 'Si le projet inclus d\'autres types de produits tel que des vidéos, des outils de travail, des événements, etc, veuillez les énumérer.',
        'description' => 'La liste détaillée de tous les autres produits d\'apprentissage prévus pour ce projet.',
        'help'        => 'Par exemple : 2 vidéos autonomes, 1 événement et 3 outils de travail.',
    ],
];
