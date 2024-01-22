<?php

use UnicaenAutoform\Provider\Privilege\AutoformindexPrivileges;

return [
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'autoform' => [
                                'label'    => 'Formulaires',
                                'route'    => 'autoform/formulaires',
                                'resource' => AutoformindexPrivileges::getResourceId(AutoformindexPrivileges::INDEX),
                                'order'    => 9230,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
?>