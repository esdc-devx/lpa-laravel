<?php

return [
    'title' => 'Plan d\'architecture',
    'tabs' => [
        'planned_product' => 'Produits prévus',
        'comments'        => 'Commentaires',
    ],
    'type' => [
        'label'       => 'Type de produit d’apprentissage',
        'description' => 'Le type de produit d’apprentissage prévu.',
        'help'        => '
            <ul>
                <li><span>Cours / Apprentissage avec instructeur</span> : Formation en salle de classe, virtuelle, ou simultané, avec un instructeur qui donne un cours en direct.</li>
                <li><span>Cours / Apprentissage à distance</span> : Les apprenants se connectent à un environnement d’apprentissage en ligne (p. ex. Moodle, Communauté SHGA, GCconnex) pour accéder au contenu des cours au moment qui leur convient. L’apprentissage à distance offre un espace d’apprentissage dans lequel les apprenants et l’instructeur peuvent interagir dans des salons de clavardage. En général, les cours sont dirigés par un instructeur et donnés sur une période de temps précise (p. ex. deux semaines) et à un groupe définit d’apprenants.</li>
                <li><span>Cours / En ligne – à rythme libre</span> : Les participants accèdent aux activités d’apprentissage en ligne. Ils mènent leur formation à leur propre rythme, sans interactions avec un instructeur ou d’autres apprenants.</li>
            </ul>
        ',
    ],
    'quantity' => [
        'label'       => 'Quantité',
        'description' => 'Le nombre de produits d’apprentissage prévus de ce type.',
    ],
    'comment' => [
        'label'       => 'Commentaires généraux',
        'instruction' => 'Si le projet inclus d\'autres types de produits tel que des vidéos, des outils de travail, des événements, etc, veuillez donner leur nombre respectif.',
        'description' => 'Tout autre renseignement pertinent relatif au plan d’architecture.',
    ],
];
