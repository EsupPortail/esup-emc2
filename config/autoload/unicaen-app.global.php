<?php
/**
 * Configuration globale du module UnicaenApp.
 *
 * Copiez ce fichier dans le répertoire "config/autoload" de l'application,
 * enlevez l'extension ".dist" et adaptez son contenu à vos besoins.
 */
return [
    'unicaen-app' => [

        /**
         * Informations concernant cette application
         */
        'app_infos' => [
            'nom'     => "PrEECoG",
            'desc'    => "Gestion Prévisionnelle des effectifs, des emplois et des compétences",
            'version' => "0.1.0",
            'date'    => "24/12/2018",
            'contact' => ['mail' => "dsi.applications@unicaen.fr", /*'tel' => "01 02 03 04 05"*/],
            'mentionsLegales'        => "http://www.unicaen.fr/acces-direct/mentions-legales/",
            'informatiqueEtLibertes' => "http://www.unicaen.fr/acces-direct/informatique-et-libertes/",
        ],

        /**
         * Période d'exécution de la requête de rafraîchissement de la session utilisateur, en millisecondes.
         */
        'session_refresh_period' => 0, // 0 <=> aucune requête exécutée

        /**
         * Paramètres de fonctionnement LDAP.
         */
        'ldap' => [
            'dn' => [
                'UTILISATEURS_BASE_DN'                  => 'ou=people,dc=unicaen,dc=fr',
                'UTILISATEURS_DESACTIVES_BASE_DN'       => 'ou=deactivated,dc=unicaen,dc=fr',
                'GROUPS_BASE_DN'                        => 'ou=groups,dc=unicaen,dc=fr',
                'STRUCTURES_BASE_DN'                    => 'ou=structures,dc=unicaen,dc=fr',
            ],
            'filters' => [
                'LOGIN_FILTER'                          => '(supannAliasLogin=%s)',
                'UTILISATEUR_STD_FILTER'                => '(|(uid=p*)(&(uid=e*)(eduPersonAffiliation=student)))',
                'CN_FILTER'                             => '(cn=%s)',
                'NAME_FILTER'                           => '(cn=%s*)',
                'UID_FILTER'                            => '(uid=%s)',
                'NO_INDIVIDU_FILTER'                    => '(supannEmpId=%08s)',
                'AFFECTATION_FILTER'                    => '(&(uid=*)(eduPersonOrgUnitDN=%s))',
                'AFFECTATION_CSTRUCT_FILTER'            => '(&(uid=*)(|(ucbnSousStructure=%s;*)(supannAffectation=%s;*)))',
                'LOGIN_OR_NAME_FILTER'                  => '(|(supannAliasLogin=%s)(cn=%s*))',
                'MEMBERSHIP_FILTER'                     => '(memberOf=%s)',
                'AFFECTATION_ORG_UNIT_FILTER'           => '(eduPersonOrgUnitDN=%s)',
                'AFFECTATION_ORG_UNIT_PRIMARY_FILTER'   => '(eduPersonPrimaryOrgUnitDN=%s)',
                'ROLE_FILTER'                           => '(supannRoleEntite=[role={SUPANN}%s][type={SUPANN}%s][code=%s]*)',
                'PROF_STRUCTURE'                        => '(&(eduPersonAffiliation=teacher)(eduPersonOrgUnitDN=%s))',
                'FILTER_STRUCTURE_DN'		            => '(%s)',
                'FILTER_STRUCTURE_CODE_ENTITE'	        => '(supannCodeEntite=%s)',
                'FILTER_STRUCTURE_CODE_ENTITE_PARENT'   => '(supannCodeEntiteParent=%s)',
            ],
        ],
    ],
];
