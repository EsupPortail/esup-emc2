<?php

/*
 * Fichier à copier/coller dans config/autoload/unicaen-bddadmin.global.php
 *
 * Les options commentées ici sont placées pour illustrer les valeurs par défaut
 * A décommenter et modifier le cas échéant
 */


return [
    'unicaen-bddadmin' => [
        'ddl' => [
            /* Répertoire où placer votre DDL */
            'dir'                    => 'database/ddl',

            /* Nom par défaut du fichier de sauvegarde des positionnements de colonnes */
            'columns_positions_file' => 'database/ddl_columns_pos.php',

            /* array général des filtres de DDL à appliquer afin d'ignorer ou bien de forcer la prise en compte de certains objets lors de la mise à jour
             * le format d'array doit respecter la spécification des DdlFilters
             * Ce tableau des filtres est utilisé aussi bien en MAJ DDL qu'en MAJ BDD
             */
            'filters'                => [],

            /* array des filtres dédié à la mise à jour de la base de données à partir de la DDL
             * le format d'array doit respecter la spécification des DdlFilters
             * pour les update-bdd, le mode EXPLICIT est forcé : c'est à dire que ce qui n'est pas spécifié dans le filtre n'existe pas
             * le filtre est initialisé avec les objets déjà présents en DDL
             */
            'update-bdd-filters'     => [],

            /* array des filtres dédié à la mise à jour de la DDL, afin d'éviter que ne se retrouvent en DDL certains objets présents en base
             * le format d'array doit respecter la spécification des DdlFilters
             */
            'update-ddl-filters'     => [],
        ],

        'data'      => [
            'config'  => [
                //UNICAEN UTILISATEUR
                'unicaen_utilisateur_user' => [
                    'actions' => ['install'],
                    'key' => 'username',
                    'options' => [],
                ],
                'unicaen_utilisateur_role' => [
                    'actions' => ['install'],
                    'key' => 'role_id',
                    'options' => [],
                ],
                'unicaen_utilisateur_role_linker' => [
                    'actions' => ['install'],
                    'key' => ['user_id', 'role_id'],
                    'options' => [
                        'columns' => [
                            'role_id' => ['transformer' => 'select id from unicaen_utilisateur_role where role_id = %s'],
                            'user_id' => ['transformer' => 'select id from unicaen_utilisateur_user where username = %s'],
                        ],
                    ],
                ],
                //UNICAEN PRIVILEGE
                'unicaen_privilege_categorie' => [
                    'actions' => ['install'],
                    'key' => 'code',
                    'options' => [],
                ],
                'unicaen_privilege_privilege' => [
                    'actions' => ['install'],
                    'key' => ['categorie_id' ,'code'],
                    'options' => [
                        'columns' => [
                            'categorie_id' => ['transformer' => 'select id from unicaen_privilege_categorie where code = %s'],
                        ],
                    ],
                ],
                'unicaen_privilege_privilege_role_linker' => [
                    'actions' => ['install'],
                    'key' => ['role_id', 'privilege_id'],
                    'options' => [
                        'columns' => [
                            'role_id' => ['transformer' => 'select id from unicaen_utilisateur_role where role_id = %s'],
                            'privilege_id' => ['transformer' => 'select id from unicaen_privilege_privilege where code = %s'],
                        ],
                    ],
                ],
                //UNICAEN ETAT
                'unicaen_etat_categorie' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
                'unicaen_etat_type' => [
                    'actions' => ['install'],
                    'key' => ['categorie_id','code'],
                    'options' => [
                        'columns' => [
                            'categorie_id' => ['transformer' => 'select id from unicaen_etat_categorie where code = %s'],
                        ],
                    ],
                ],
                //UNICAEN EVENEMENT
                'unicaen_evenement_etat' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
                'unicaen_evenement_type' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
                // UNICAEN PARAMETRE
                'unicaen_parametre_categorie' => [
                    'actions' => ['install'],
                    'key' => 'code',
                    'options' => [],
                ],
                'unicaen_parametre_parametre' => [
                    'actions' => ['install'],
                    'key' => ['categorie_id' ,'code'],
                    'options' => [
                        'columns' => [
                            'categorie_id' => ['transformer' => 'select id from unicaen_parametre_categorie where code = %s'],
                        ],
                    ],
                ],
                // UNICAEN AUTOFORM
                'unicaen_autoform_formulaire' => [
                    'actions' => ['install'],
                    'key' => 'code',
                    'options' => [],
                ],
                'unicaen_autoform_categorie' => [
                    'actions' => ['install'],
                    'key' => ['formulaire' ,'code'],
                    'options' => [
                        'columns' => [
                            'formulaire' => ['transformer' => 'select id from unicaen_autoform_formulaire where code = %s'],
                        ],
                    ],
                ],
                'unicaen_autoform_champ_type' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [
                        'columns' => [],
                    ],
                ],
                'unicaen_autoform_champ' => [
                    'actions' => ['install'],
                    'key' => ['categorie', 'code'],
                    'options' => [
                        'columns' => [
                            'categorie' => ['transformer' => 'select id from unicaen_autoform_categorie where code = %s'],
                            'element' => ['transformer' => 'select code from demo.public.unicaen_autoform_champ_type where code = %s'],
                            //todo element
                        ],
                    ],
                ],
                //UNICAEN RENDERER
                'unicaen_renderer_macro' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
                'unicaen_renderer_template' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
                //UNICAEN VALIDATION
                'unicaen_validation_type' => [
                    'actions' => ['install'],
                    'key' => ['code'],
                    'options' => [],
                ],
            ],
            'sources' => [
                'database/sources/unicaen_utilisateur_user.php',
                'database/sources/unicaen_utilisateur_role.php',
                'database/sources/unicaen_utilisateur_role_linker.php',
                'database/sources/unicaen_privilege_categorie.php',
                'database/sources/unicaen_privilege_privilege.php',
                'database/sources/unicaen_privilege_privilege_role_linker.php',
                'database/sources/unicaen_autoform_formulaire.php',
                'database/sources/unicaen_autoform_categorie.php',
                'database/sources/unicaen_autoform_champ_type.php',
                'database/sources/unicaen_autoform_champ.php',
                'database/sources/unicaen_etat_categorie.php',
                'database/sources/unicaen_etat_type.php',
                'database/sources/unicaen_evenement_etat.php',
                'database/sources/unicaen_evenement_type.php',
                'database/sources/unicaen_parametre_categorie.php',
                'database/sources/unicaen_parametre_parametre.php',
                'database/sources/unicaen_renderer_macro.php',
                'database/sources/unicaen_renderer_template.php',
                'database/sources/unicaen_validation_type.php',
            ],
        ],
        'migration' => [],

        /* Nom des colonnes servant de clé primaire dans vos tables, généralement 'id' pour la compatibilité avec Doctrine
         * Si des tables n'ont pas de colonne 'id' ou personnalisé, le système fonctionnera sans utiliser les séquences pour initialiser la clé primaire
         */
        //'id_column' => 'id',

        'histo' => [
            /* ID par défaut de l'utilisateur utilisé par le DataManager pour insérer ou modifier les données
             * Peut être fourni ici ou bien dans une factory adaptée en utilisant la méthode suivante :
             *
             * $config = [...config de bddAdmin...];
             * $bdd = new Unicaen\BddAdmin\Bdd($config);
             *
             * $monUsername = 'mon_username';
             * $monId = $bdd->selectOne('SELECT id FROM utilisateur WHERE username=:username', ['username' => $monUsername], 'id');
             * $bdd->setHistoUserId($monId);
             *
             * Si user_id est NULL, cette fonctionnalité d'historisation sera désactivée
             */
            'user_id' => 0,

            /* Noms des colonnes utilisées pour gérer les historiques
             * Attention : tous les noms doivent être renseignés ou alors tous mis à NULL si pas de gestion d'historiques
             * Se base par défaut sur ce qui est préconisé pour UnicaenApp\Entity\HistoriqueAwareInterface
             *
             * Si vos tables ne possèdent pas l'ensemble de ces colonnes, la gestion de l'historique ne sera pas appliquée sur celles-ci
             */
//            'histo_creation_column'        => 'histo_creation',
//            'histo_createur_id_column'     => 'histo_createur_id',
            //'histo_modificateur_id_column' => 'histo_modificateur_id',
            //'histo_destructeur_id_column'  => 'histo_destructeur_id',
            //'histo_destruction_column'     => 'histo_destruction',
            //'histo_modification_column'    => 'histo_modification',
        ],

        'import'             => [
            /* Compatibilité avec un système d'import de données
             * ID de la source par défaut utilisée par le DataManager pour insérer une ligne d'une table synchronisable
             * Peut être fourni ici ou bien dans une factory adaptée en utilisant la méthode suivante :
             *
             * $config = [...config de bddAdmin...];
             * $bdd = new Unicaen\BddAdmin\Bdd($config);
             *
             * $code = 'ma_source';
             * $monId = $bdd->selectOne('SELECT id FROM source WHERE code=:code', ['code' => $code], 'id');
             * $bdd->setSourceId($monId);
             *
             * Si source_id est NULL, cette fonctionnalité d'initialisation de sources sera désactivée
             *
             */
            //'source_id'          => null,

            /* Noms des colonnes utilisées pour gérer les données liées à l'import depuis d'autres logiciels
             * Attention : tous les noms doivent être renseignés ou alors tous mis à NULL si pas de gestion d'import
             *
             * Si vos tables ne possèdent pas l'ensemble de ces colonnes, la gestion des colonnes d'import ne sera pas appliquée sur celles-ci
             */
            //'source_id_column'   => 'source_id',
            //'source_code_column' => 'source_code',
        ],

        /* Connexion à utiliser par défaut, nom à sélectionner parmi la liste des connexions disponibles */
        'current_connection' => 'default',
    ],
];