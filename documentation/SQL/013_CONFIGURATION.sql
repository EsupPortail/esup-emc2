create table configuration_entretienpro
(
    id                    serial
        constraint configuration_entretienpro_pk
        primary key,
    operation             varchar(64)  not null,
    valeur                varchar(128) not null,
    histo_creation        timestamp    not null,
    histo_createur_id     integer      not null
        constraint configuration_entretienpro_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp    not null,
    histo_modificateur_id integer      not null
        constraint configuration_entretienpro_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint configuration_entretienpro_destructeur_fk
        references unicaen_utilisateur_user
);

create unique index configuration_entretienpro_id_uindex
    on configuration_entretienpro (id);

create table configuration_fichemetier
(
    id                    serial
        constraint configuration_fichemetier_pk
        primary key,
    operation             varchar(64) not null,
    entity_type           varchar(255),
    entity_id             varchar(255),
    histo_creation        timestamp   not null,
    histo_createur_id     integer     not null
        constraint configuration_fichemetier_createur_fk
        references unicaen_utilisateur_user,
    histo_modification    timestamp   not null,
    histo_modificateur_id integer     not null
        constraint configuration_fichemetier_modificateur_fk
        references unicaen_utilisateur_user,
    histo_destruction     timestamp,
    histo_destructeur_id  integer
        constraint configuration_fichemetier_destructeur_fk
        references unicaen_utilisateur_user
);




-- IIIIIIIIIINNNNNNNN        NNNNNNNN   SSSSSSSSSSSSSSS EEEEEEEEEEEEEEEEEEEEEERRRRRRRRRRRRRRRRR   TTTTTTTTTTTTTTTTTTTTTTT
-- I::::::::IN:::::::N       N::::::N SS:::::::::::::::SE::::::::::::::::::::ER::::::::::::::::R  T:::::::::::::::::::::T
-- I::::::::IN::::::::N      N::::::NS:::::SSSSSS::::::SE::::::::::::::::::::ER::::::RRRRRR:::::R T:::::::::::::::::::::T
-- II::::::IIN:::::::::N     N::::::NS:::::S     SSSSSSSEE::::::EEEEEEEEE::::ERR:::::R     R:::::RT:::::TT:::::::TT:::::T
--   I::::I  N::::::::::N    N::::::NS:::::S              E:::::E       EEEEEE  R::::R     R:::::RTTTTTT  T:::::T  TTTTTT
--   I::::I  N:::::::::::N   N::::::NS:::::S              E:::::E               R::::R     R:::::R        T:::::T
--   I::::I  N:::::::N::::N  N::::::N S::::SSSS           E::::::EEEEEEEEEE     R::::RRRRRR:::::R         T:::::T
--   I::::I  N::::::N N::::N N::::::N  SS::::::SSSSS      E:::::::::::::::E     R:::::::::::::RR          T:::::T
--   I::::I  N::::::N  N::::N:::::::N    SSS::::::::SS    E:::::::::::::::E     R::::RRRRRR:::::R         T:::::T
--   I::::I  N::::::N   N:::::::::::N       SSSSSS::::S   E::::::EEEEEEEEEE     R::::R     R:::::R        T:::::T
--   I::::I  N::::::N    N::::::::::N            S:::::S  E:::::E               R::::R     R:::::R        T:::::T
--   I::::I  N::::::N     N:::::::::N            S:::::S  E:::::E       EEEEEE  R::::R     R:::::R        T:::::T
-- II::::::IIN::::::N      N::::::::NSSSSSSS     S:::::SEE::::::EEEEEEEE:::::ERR:::::R     R:::::R      TT:::::::TT
-- I::::::::IN::::::N       N:::::::NS::::::SSSSSS:::::SE::::::::::::::::::::ER::::::R     R:::::R      T:::::::::T
-- I::::::::IN::::::N        N::::::NS:::::::::::::::SS E::::::::::::::::::::ER::::::R     R:::::R      T:::::::::T
-- IIIIIIIIIINNNNNNNN         NNNNNNN SSSSSSSSSSSSSSS   EEEEEEEEEEEEEEEEEEEEEERRRRRRRR     RRRRRRR      TTTTTTTTTTT

INSERT INTO unicaen_privilege_categorie (code, libelle, ordre, namespace)
VALUES ('configuration', 'Configuration de l''application', 1100, 'Application\Provider\Privilege');
INSERT INTO unicaen_privilege_privilege(CATEGORIE_ID, CODE, LIBELLE, ORDRE)
WITH d(code, lib, ordre) AS (
    SELECT 'configuration_afficher', 'Afficher la configuration de l''application', 1 UNION
    SELECT 'configuration_ajouter', 'Ajouter des éléments à la configuration', 2 UNION
    SELECT 'configuration_detruire', 'Rétirer des éléments à la configuration', 3 UNION
    SELECT 'configuration_reappliquer', 'Ré-appliquer les éléments à ajouter ', 4
)
SELECT cp.id, d.code, d.lib, d.ordre
FROM d
JOIN unicaen_privilege_categorie cp ON cp.CODE = 'configuration';