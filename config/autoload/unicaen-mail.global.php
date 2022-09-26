<?php

use UnicaenMail\Controller\MailController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'mail' => [
                                'label' => 'Courriers électroniques',
                                'route' => 'mail',
                                'resource' => PrivilegeController::getResourceId(MailController::class, 'index'),
                                'order'    => 9003,
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