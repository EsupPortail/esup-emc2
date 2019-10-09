<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    /**
     * Configuration globale du module UnicaenDbImport.
     */
    'import' => [
        //
        // Connexions.
        //
        'connections' => [
            //
            // Bases de données.
            // Format: 'identifiant unique' => 'nom de la connexion Doctrine'
            //
            'default' => 'doctrine.connection.orm_default',
            'octopus' => 'doctrine.connection.orm_octopus',
        ],
        /**
         * Liste des imports.
         */
        'synchros' => [
//            [
//                'name' => 'Import_AGENT',
                /**
                 * Configuration de la source de données à importer :
                 * - 'name'               : petit nom (unique) de la source
                 * - 'table'              : nom de la table source contenant les données à importer
                 * - 'select'             : select SQL de mise en forme des données source à importer (NB: antinomique avec 'table')
                 * - 'connection'         : identifiant de la connexion Doctrine à la base de données source
                 * - 'source_code_column' : nom de la colonne dans la table/vue source contenant l'identifiant unique
                 * - 'columns'            : liste ordonnée des noms des colonnes à prendre en compte dans la table/vue source
                 */
//                'source' => [
//                    'name'               => 'Agents geres par la DRH',
//                    'table'              => 'V_AGENT',
//                    'select'              => 'SELECT * FROM V_AGENT',
//                    'connection'         => 'octopus',
//                    'source_code_column' => 'C_INDIVIDU',
//                    'columns'            => ['C_SRC_INDIVIDU', 'C_SOURCE', 'PRENOM', 'NOM_USAGE'],
//                ],
                /**
                 * Forçage éventuel du nom de la table intermédiaire utilisée lorsque source et destination
                 * ne partagent pas la même connexion. NB: cette table intermédiaire est créée/peuplée/supprimée
                 * dans la base de données destination à chaque import.
                 * En l'absence de ce forçage, le nom de la table intermédiaire sera celui de la table destination
                 * préfixé par "src_".
                 */
//                'intermediate_table' => 'src_agent',
                /**
                 * Configuration de la destination des données importées :
                 * - 'name'               : petit nom (unique) de la destination
                 * - 'table'              : nom de la table destination vers laquelle les données sont importées
                 * - 'connection'         : identifiant de la connexion Doctrine à la base de données destination
                 * - 'source_code_column' : nom de la colonne dans la table destination contenant l'identifiant unique
                 * - 'columns'            : liste ordonnée des noms des colonnes importées dans la table destination
                 * - 'columns_to_char'    : format sprintf nécessaire pour mettre des colonnes au format requis (string)
                 */
//                'destination' => [
//                    'name'               => 'Agents gérés par la DRH',
//                    'table'              => 'agent',
//                    'connection'         => 'default',
//                    'source_code_column' => 'c_individu',
//                    'columns'            => ['c_src_individu', 'c_source', 'prenom', 'nom_usage'],
//                    'columns_to_char' => [
//                        'debut_validite' => "TO_CHAR(%s,'YYYY-MM-DD')", // car colonne destination de type TIMESTAMP
//                        'fin_validite'   => "TO_CHAR(%s,'YYYY-MM-DD')", // idem
//                    ],
//                ],
//            ],
            [
                'name' => 'Import_AGENT',
                'source' => [
                    'name'               => 'Agents geres par la DRH',
                    'select'             => 'SELECT * FROM V_PREECOG_AGENT',
                    'connection'         => 'octopus',
                    'source_code_column' => 'C_INDIVIDU',
//                    'columns'            => ['C_SRC_INDIVIDU', 'C_SOURCE', 'PRENOM', 'NOM_USAGE'],
                ],
//                'intermediate_table' => 'src_agent',
                'destination' => [
                    'name'               => 'Agents gérés par la DRH',
                    'table'              => 'agent',
                    'connection'         => 'default',
                    'source_code_column' => 'c_individu',
//                    'columns'            => ['c_src_individu', 'c_source', 'prenom', 'nom_usage'],
                ],
            ],
            [
                'name' => 'Import_AGENT_GRADE',
                'source' => [
                    'name'               => 'Grades liés aux agents de PreeCog',
                    'select'             => 'SELECT * FROM V_PREECOG_AGENT_GRADE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'AG_ID',
                    'columns'            => ['AGENT_ID', 'STRUCTURE_ID', 'CORPS_ID', 'GRADE_ID', 'BAP_ID', 'DATE_DEBUT', 'DATE_FIN'],
                ],
                'intermediate_table' => 'src_agent_grade',
                'destination' => [
                    'name'               => 'Grade des agents gérés par la DRH',
                    'table'              => 'agent_grade',
                    'connection'         => 'default',
                    'source_code_column' => 'ag_id',
                    'columns'            => ['agent_id', 'structure_id', 'corps_id', 'grade_id', 'bap_id', 'date_debut', 'date_fin'],
                ],
            ],
            [
                'name' => 'Import_STATUT',
                'source' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'select'             => 'SELECT * FROM V_PREECOG_STATUT',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['ID_ORIG', 'C_SOURCE', 'INDIVIDU_ID', 'STRUCTURE_ID', 'D_DEBUT', 'D_FIN', 'T_TITULAIRE', 'T_CDI', 'T_CDD', 'T_VACATAIRE', 'T_ENSEIGNANT', 'T_ADMINISTRATIF', 'T_CHERCHEUR', 'T_ETUDIANT', 'T_AUDITEUR_LIBRE', 'T_DOCTORANT', 'T_DETACHE_IN', 'T_DETACHE_OUT', 'T_DISPO', 'T_HEBERGE', 'T_EMERITE', 'T_RETRAITE'],
                ],
                'intermediate_table' => 'src_agent_statut',
                'destination' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'table'              => 'agent_statut',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['id_orig', 'c_source', 'individu_id', 'structure_id', 'd_debut', 'd_fin', 't_titulaire', 't_cdi', 't_cdd', 't_vacataire', 't_enseignant', 't_administratif', 't_chercheur', 't_etudiant', 't_auditeur_libre', 't_doctorant', 't_detache_in', 't_detache_out', 't_dispo', 't_heberge', 't_emerite', 't_retraite'],
                    'columns_to_char' => [
                        'd_debut' => "TO_CHAR(%s,'YYYY-MM-DD')",
                        'd_fin'   => "TO_CHAR(%s,'YYYY-MM-DD')",
                    ],
                ],
            ],
            [
                'name' => 'Import_STRUCTURE',
                'source' => [
                    'name'               => 'Agents geres par la DRH',
                    'select'             => 'SELECT * FROM V_PREECOG_STRUCTURE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['CODE', 'LIBELLE_COURT', 'LIBELLE_LONG', 'TYPE', 'HISTO'],
                ],
                'intermediate_table' => 'src_structure',
                'destination' => [
                    'name'               => 'Structure stockees dans octopus',
                    'table'              => 'structure',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['code', 'libelle_court', 'libelle_long', 'type', 'histo'],
                ],
            ],
            [
                'name' => 'Import_SITE',
                'source' => [
                    'name'               => 'Sites remontés de PrEECoG',
                    'select'             => 'SELECT * FROM V_PREECOG_SITE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['NOM', 'LIBELLE'],
                ],
                'intermediate_table' => 'src_site',
                'destination' => [
                    'name'               => 'Sites stockes dans octopus',
                    'table'              => 'site',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['nom', 'libelle'],
                ],
            ],
            [
                'name' => 'Import_BATIMENT',
                'source' => [
                    'name'               => 'Batiments remontés de PrEECoG',
                    'select'             => 'SELECT * FROM V_PREECOG_BATIMENT',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['NOM', 'LIBELLE', 'SITE_ID'],
                ],
                'intermediate_table' => 'src_batiment',
                'destination' => [
                    'name'               => 'Batiments stockes dans octopus',
                    'table'              => 'batiment',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['nom', 'libelle', 'site_id'],
                ],
            ],
            [
                'name' => 'Import_FONCTION',
                'source' => [
                    'name'               => 'Fonctions remontées de PrEECoG',
                    'select'             => 'SELECT * FROM V_PREECOG_FONCTION',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['PARENT_ID', 'CODE', 'NIVEAU'],
                ],
                'intermediate_table' => 'src_fonction',
                'destination' => [
                    'name'               => 'Fonctions remontées de OCTOPUS',
                    'table'              => 'fonction',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['parent_id', 'code', 'niveau'],
                ],
            ],
            [
                'name' => 'Import_FONCTION_LIBELLE',
                'source' => [
                    'name'               => 'Libellés des fonctions remontées de PrEECoG',
                    'select'             => 'SELECT * FROM V_PREECOG_FONCTION_LIBELLE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['FONCTION_ID', 'LIBELLE', 'GENRE', 'DEFAUT'],
                ],
                'intermediate_table' => 'src_fonction_libelle',
                'destination' => [
                    'name'               => 'Libellés des fonctions remontées de OCTOPUS',
                    'table'              => 'fonction_libelle',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['fonction_id', 'libelle', 'genre', 'defaut'],
                ],
            ],
            [
                'name' => 'Import_BAP',
                'source' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT * FROM V_PREECOG_BAP',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['C_BAP', 'LIB_COURT', 'LIB_LONG', 'HISTO'],
                ],
                'intermediate_table' => 'src_correspondance',
                'destination' => [
                    'name'               => 'BAP des agents remonté depuis OCTOPUS',
                    'table'              => 'correspondance',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['c_bap', 'lib_court', 'lib_long', 'histo'],
                ],
            ],
            [
                'name' => 'Import_GRADE',
                'source' => [
                    'name'               => 'Grades des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT * FROM V_PREECOG_GRADE',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['LIB_COURT', 'LIB_LONG', 'CODE', 'HISTO'],
                ],
                'intermediate_table' => 'src_grade',
                'destination' => [
                    'name'               => 'Grades des agents remonté depuis OCTOPUS',
                    'table'              => 'grade',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['lib_court', 'lib_long', 'code', 'histo'],
                ],
            ],
            [
                'name' => 'Import_CORPS',
                'source' => [
                    'name'               => 'Corps des agents remonté depuis OCTOPUS',
                    'select'             => 'SELECT * FROM V_PREECOG_CORPS',
                    'connection'         => 'octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['LIB_COURT', 'LIB_LONG', 'CODE', 'CATEGORIE', 'HISTO'],
                ],
                'intermediate_table' => 'src_corps',
                'destination' => [
                    'name'               => 'Corps des agents remonté depuis OCTOPUS',
                    'table'              => 'corps',
                    'connection'         => 'default',
                    'source_code_column' => 'id',
                    'columns'            => ['lib_court', 'lib_long', 'code', 'categorie', 'histo'],
                ],
            ],
        ],

    ],

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'UnicaenDbImport\Controller\Console',
                    'action'     => [
                        'runImport',
                        'runSynchro',
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

];
