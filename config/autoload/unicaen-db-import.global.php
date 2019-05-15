<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    /**
     * Configuration globale du module UnicaenDbImport.
     */
    'import' => [
        /**
         * Liste des imports.
         */
        'imports' => [
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
//                    'connection'         => 'doctrine.connection.orm_octopus',
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
//                    'connection'         => 'doctrine.connection.orm_default',
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
                    'select'              => 'SELECT * FROM V_AGENT',
                    'connection'         => 'doctrine.connection.orm_octopus',
                    'source_code_column' => 'C_INDIVIDU',
                    'columns'            => ['C_SRC_INDIVIDU', 'C_SOURCE', 'PRENOM', 'NOM_USAGE'],
                ],
                'intermediate_table' => 'src_agent',
                'destination' => [
                    'name'               => 'Agents gérés par la DRH',
                    'table'              => 'agent',
                    'connection'         => 'doctrine.connection.orm_default',
                    'source_code_column' => 'c_individu',
                    'columns'            => ['c_src_individu', 'c_source', 'prenom', 'nom_usage'],
                ],
            ],
            [
                'name' => 'Import_STATUT',
                'source' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'select'              => 'SELECT * FROM V_STATUT',
                    'connection'         => 'doctrine.connection.orm_octopus',
                    'source_code_column' => 'ID',
                    'columns'            => ['ID_ORIG', 'C_SOURCE', 'INDIVIDU_ID', 'STRUCTURE_ID', 'D_DEBUT', 'D_FIN', 'T_TITULAIRE', 'T_CDI', 'T_CDD', 'T_VACATAIRE', 'T_ENSEIGNANT', 'T_ADMINISTRATIF', 'T_CHERCHEUR', 'T_ETUDIANT', 'T_AUDITEUR_LIBRE', 'T_DOCTORANT', 'T_DETACHE_IN', 'T_DETACHE_OUT', 'T_DISPO', 'T_HEBERGE', 'T_EMERITE', 'T_RETRAITE'],
                ],
                'intermediate_table' => 'src_agent_statut',
                'destination' => [
                    'name'               => 'Statut des agents geres par la DRH',
                    'table'              => 'agent_statut',
                    'connection'         => 'doctrine.connection.orm_default',
                    'source_code_column' => 'id',
                    'columns'            => ['id_orig', 'c_source', 'individu_id', 'structure_id', 'd_debut', 'd_fin', 't_titulaire', 't_cdi', 't_cdd', 't_vacataire', 't_enseignant', 't_administratif', 't_chercheur', 't_etudiant', 't_auditeur_libre', 't_doctorant', 't_detache_in', 't_detache_out', 't_dispo', 't_heberge', 't_emerite', 't_retraite'],
                    'columns_to_char' => [
                        'd_debut' => "TO_CHAR(%s,'YYYY-MM-DD')",
                        'd_fin'   => "TO_CHAR(%s,'YYYY-MM-DD')",
                    ],
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
                    ],
                    'roles' => [],
                ],
            ],
        ],
    ],

];
