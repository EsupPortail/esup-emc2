CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_agent_statut(src_id text, src_id_orig text, src_c_source text, src_individu_id text, src_structure_id text, src_d_debut text, src_d_fin text, src_t_titulaire text, src_t_cdi text, src_t_cdd text, src_t_vacataire text, src_t_enseignant text, src_t_administratif text, src_t_chercheur text, src_t_etudiant text, src_t_auditeur_libre text, src_t_doctorant text, src_t_detache_in text, src_t_detache_out text, src_t_dispo text, src_t_heberge text, src_t_emerite text, src_t_retraite text, dest_id text, dest_id_orig text, dest_c_source text, dest_individu_id text, dest_structure_id text, dest_d_debut text, dest_d_fin text, dest_t_titulaire text, dest_t_cdi text, dest_t_cdd text, dest_t_vacataire text, dest_t_enseignant text, dest_t_administratif text, dest_t_chercheur text, dest_t_etudiant text, dest_t_auditeur_libre text, dest_t_doctorant text, dest_t_detache_in text, dest_t_detache_out text, dest_t_dispo text, dest_t_heberge text, dest_t_emerite text, dest_t_retraite text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_ID_ORIG = coalesce(quote_literal(src_ID_ORIG), 'NULL');
    src_C_SOURCE = coalesce(quote_literal(src_C_SOURCE), 'NULL');
    src_INDIVIDU_ID = coalesce(quote_literal(src_INDIVIDU_ID), 'NULL');
    src_STRUCTURE_ID = coalesce(quote_literal(src_STRUCTURE_ID), 'NULL');
    src_D_DEBUT = coalesce(quote_literal(src_D_DEBUT), 'NULL');
    src_D_FIN = coalesce(quote_literal(src_D_FIN), 'NULL');
    src_T_TITULAIRE = coalesce(quote_literal(src_T_TITULAIRE), 'NULL');
    src_T_CDI = coalesce(quote_literal(src_T_CDI), 'NULL');
    src_T_CDD = coalesce(quote_literal(src_T_CDD), 'NULL');
    src_T_VACATAIRE = coalesce(quote_literal(src_T_VACATAIRE), 'NULL');
    src_T_ENSEIGNANT = coalesce(quote_literal(src_T_ENSEIGNANT), 'NULL');
    src_T_ADMINISTRATIF = coalesce(quote_literal(src_T_ADMINISTRATIF), 'NULL');
    src_T_CHERCHEUR = coalesce(quote_literal(src_T_CHERCHEUR), 'NULL');
    src_T_ETUDIANT = coalesce(quote_literal(src_T_ETUDIANT), 'NULL');
    src_T_AUDITEUR_LIBRE = coalesce(quote_literal(src_T_AUDITEUR_LIBRE), 'NULL');
    src_T_DOCTORANT = coalesce(quote_literal(src_T_DOCTORANT), 'NULL');
    src_T_DETACHE_IN = coalesce(quote_literal(src_T_DETACHE_IN), 'NULL');
    src_T_DETACHE_OUT = coalesce(quote_literal(src_T_DETACHE_OUT), 'NULL');
    src_T_DISPO = coalesce(quote_literal(src_T_DISPO), 'NULL');
    src_T_HEBERGE = coalesce(quote_literal(src_T_HEBERGE), 'NULL');
    src_T_EMERITE = coalesce(quote_literal(src_T_EMERITE), 'NULL');
    src_T_RETRAITE = coalesce(quote_literal(src_T_RETRAITE), 'NULL');
    dest_ID_ORIG = coalesce(quote_literal(dest_ID_ORIG), 'NULL');
    dest_C_SOURCE = coalesce(quote_literal(dest_C_SOURCE), 'NULL');
    dest_INDIVIDU_ID = coalesce(quote_literal(dest_INDIVIDU_ID), 'NULL');
    dest_STRUCTURE_ID = coalesce(quote_literal(dest_STRUCTURE_ID), 'NULL');
    dest_D_DEBUT = coalesce(quote_literal(dest_D_DEBUT), 'NULL');
    dest_D_FIN = coalesce(quote_literal(dest_D_FIN), 'NULL');
    dest_T_TITULAIRE = coalesce(quote_literal(dest_T_TITULAIRE), 'NULL');
    dest_T_CDI = coalesce(quote_literal(dest_T_CDI), 'NULL');
    dest_T_CDD = coalesce(quote_literal(dest_T_CDD), 'NULL');
    dest_T_VACATAIRE = coalesce(quote_literal(dest_T_VACATAIRE), 'NULL');
    dest_T_ENSEIGNANT = coalesce(quote_literal(dest_T_ENSEIGNANT), 'NULL');
    dest_T_ADMINISTRATIF = coalesce(quote_literal(dest_T_ADMINISTRATIF), 'NULL');
    dest_T_CHERCHEUR = coalesce(quote_literal(dest_T_CHERCHEUR), 'NULL');
    dest_T_ETUDIANT = coalesce(quote_literal(dest_T_ETUDIANT), 'NULL');
    dest_T_AUDITEUR_LIBRE = coalesce(quote_literal(dest_T_AUDITEUR_LIBRE), 'NULL');
    dest_T_DOCTORANT = coalesce(quote_literal(dest_T_DOCTORANT), 'NULL');
    dest_T_DETACHE_IN = coalesce(quote_literal(dest_T_DETACHE_IN), 'NULL');
    dest_T_DETACHE_OUT = coalesce(quote_literal(dest_T_DETACHE_OUT), 'NULL');
    dest_T_DISPO = coalesce(quote_literal(dest_T_DISPO), 'NULL');
    dest_T_HEBERGE = coalesce(quote_literal(dest_T_HEBERGE), 'NULL');
    dest_T_EMERITE = coalesce(quote_literal(dest_T_EMERITE), 'NULL');
    dest_T_RETRAITE = coalesce(quote_literal(dest_T_RETRAITE), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO agent_statut(ID, ID_ORIG, C_SOURCE, INDIVIDU_ID, STRUCTURE_ID, D_DEBUT, D_FIN, T_TITULAIRE, T_CDI, T_CDD, T_VACATAIRE, T_ENSEIGNANT, T_ADMINISTRATIF, T_CHERCHEUR, T_ETUDIANT, T_AUDITEUR_LIBRE, T_DOCTORANT, T_DETACHE_IN, T_DETACHE_OUT, T_DISPO, T_HEBERGE, T_EMERITE, T_RETRAITE, created_on) VALUES (' || quote_literal(src_ID) || ', ' || src_ID_ORIG || ', ' || src_C_SOURCE || ', ' || src_INDIVIDU_ID || ', ' || src_STRUCTURE_ID || ', ' || src_D_DEBUT || ', ' || src_D_FIN || ', ' || src_T_TITULAIRE || ', ' || src_T_CDI || ', ' || src_T_CDD || ', ' || src_T_VACATAIRE || ', ' || src_T_ENSEIGNANT || ', ' || src_T_ADMINISTRATIF || ', ' || src_T_CHERCHEUR || ', ' || src_T_ETUDIANT || ', ' || src_T_AUDITEUR_LIBRE || ', ' || src_T_DOCTORANT || ', ' || src_T_DETACHE_IN || ', ' || src_T_DETACHE_OUT || ', ' || src_T_DISPO || ', ' || src_T_HEBERGE || ', ' || src_T_EMERITE || ', ' || src_T_RETRAITE || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'agent_statut', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'ID_ORIG' doit être mis à jour
        IF (src_ID_ORIG <> dest_ID_ORIG) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_ID_ORIG || '-' || import_hash;
            sql = 'UPDATE agent_statut SET ID_ORIG = ' || src_ID_ORIG || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'ID_ORIG', src_ID_ORIG, dest_ID_ORIG, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'C_SOURCE' doit être mis à jour
        IF (src_C_SOURCE <> dest_C_SOURCE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_C_SOURCE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET C_SOURCE = ' || src_C_SOURCE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'C_SOURCE', src_C_SOURCE, dest_C_SOURCE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'INDIVIDU_ID' doit être mis à jour
        IF (src_INDIVIDU_ID <> dest_INDIVIDU_ID) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_INDIVIDU_ID || '-' || import_hash;
            sql = 'UPDATE agent_statut SET INDIVIDU_ID = ' || src_INDIVIDU_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'INDIVIDU_ID', src_INDIVIDU_ID, dest_INDIVIDU_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'STRUCTURE_ID' doit être mis à jour
        IF (src_STRUCTURE_ID <> dest_STRUCTURE_ID) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_STRUCTURE_ID || '-' || import_hash;
            sql = 'UPDATE agent_statut SET STRUCTURE_ID = ' || src_STRUCTURE_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'STRUCTURE_ID', src_STRUCTURE_ID, dest_STRUCTURE_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'D_DEBUT' doit être mis à jour
        IF (src_D_DEBUT <> dest_D_DEBUT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_D_DEBUT || '-' || import_hash;
            sql = 'UPDATE agent_statut SET D_DEBUT = ' || src_D_DEBUT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'D_DEBUT', src_D_DEBUT, dest_D_DEBUT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'D_FIN' doit être mis à jour
        IF (src_D_FIN <> dest_D_FIN) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_D_FIN || '-' || import_hash;
            sql = 'UPDATE agent_statut SET D_FIN = ' || src_D_FIN || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'D_FIN', src_D_FIN, dest_D_FIN, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_TITULAIRE' doit être mis à jour
        IF (src_T_TITULAIRE <> dest_T_TITULAIRE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_TITULAIRE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_TITULAIRE = ' || src_T_TITULAIRE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_TITULAIRE', src_T_TITULAIRE, dest_T_TITULAIRE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_CDI' doit être mis à jour
        IF (src_T_CDI <> dest_T_CDI) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_CDI || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_CDI = ' || src_T_CDI || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_CDI', src_T_CDI, dest_T_CDI, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_CDD' doit être mis à jour
        IF (src_T_CDD <> dest_T_CDD) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_CDD || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_CDD = ' || src_T_CDD || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_CDD', src_T_CDD, dest_T_CDD, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_VACATAIRE' doit être mis à jour
        IF (src_T_VACATAIRE <> dest_T_VACATAIRE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_VACATAIRE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_VACATAIRE = ' || src_T_VACATAIRE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_VACATAIRE', src_T_VACATAIRE, dest_T_VACATAIRE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_ENSEIGNANT' doit être mis à jour
        IF (src_T_ENSEIGNANT <> dest_T_ENSEIGNANT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_ENSEIGNANT || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_ENSEIGNANT = ' || src_T_ENSEIGNANT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_ENSEIGNANT', src_T_ENSEIGNANT, dest_T_ENSEIGNANT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_ADMINISTRATIF' doit être mis à jour
        IF (src_T_ADMINISTRATIF <> dest_T_ADMINISTRATIF) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_ADMINISTRATIF || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_ADMINISTRATIF = ' || src_T_ADMINISTRATIF || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_ADMINISTRATIF', src_T_ADMINISTRATIF, dest_T_ADMINISTRATIF, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_CHERCHEUR' doit être mis à jour
        IF (src_T_CHERCHEUR <> dest_T_CHERCHEUR) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_CHERCHEUR || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_CHERCHEUR = ' || src_T_CHERCHEUR || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_CHERCHEUR', src_T_CHERCHEUR, dest_T_CHERCHEUR, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_ETUDIANT' doit être mis à jour
        IF (src_T_ETUDIANT <> dest_T_ETUDIANT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_ETUDIANT || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_ETUDIANT = ' || src_T_ETUDIANT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_ETUDIANT', src_T_ETUDIANT, dest_T_ETUDIANT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_AUDITEUR_LIBRE' doit être mis à jour
        IF (src_T_AUDITEUR_LIBRE <> dest_T_AUDITEUR_LIBRE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_AUDITEUR_LIBRE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_AUDITEUR_LIBRE = ' || src_T_AUDITEUR_LIBRE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_AUDITEUR_LIBRE', src_T_AUDITEUR_LIBRE, dest_T_AUDITEUR_LIBRE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_DOCTORANT' doit être mis à jour
        IF (src_T_DOCTORANT <> dest_T_DOCTORANT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_DOCTORANT || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_DOCTORANT = ' || src_T_DOCTORANT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_DOCTORANT', src_T_DOCTORANT, dest_T_DOCTORANT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_DETACHE_IN' doit être mis à jour
        IF (src_T_DETACHE_IN <> dest_T_DETACHE_IN) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_DETACHE_IN || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_DETACHE_IN = ' || src_T_DETACHE_IN || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_DETACHE_IN', src_T_DETACHE_IN, dest_T_DETACHE_IN, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_DETACHE_OUT' doit être mis à jour
        IF (src_T_DETACHE_OUT <> dest_T_DETACHE_OUT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_DETACHE_OUT || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_DETACHE_OUT = ' || src_T_DETACHE_OUT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_DETACHE_OUT', src_T_DETACHE_OUT, dest_T_DETACHE_OUT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_DISPO' doit être mis à jour
        IF (src_T_DISPO <> dest_T_DISPO) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_DISPO || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_DISPO = ' || src_T_DISPO || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_DISPO', src_T_DISPO, dest_T_DISPO, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_HEBERGE' doit être mis à jour
        IF (src_T_HEBERGE <> dest_T_HEBERGE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_HEBERGE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_HEBERGE = ' || src_T_HEBERGE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_HEBERGE', src_T_HEBERGE, dest_T_HEBERGE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_EMERITE' doit être mis à jour
        IF (src_T_EMERITE <> dest_T_EMERITE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_EMERITE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_EMERITE = ' || src_T_EMERITE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_EMERITE', src_T_EMERITE, dest_T_EMERITE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'T_RETRAITE' doit être mis à jour
        IF (src_T_RETRAITE <> dest_T_RETRAITE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_T_RETRAITE || '-' || import_hash;
            sql = 'UPDATE agent_statut SET T_RETRAITE = ' || src_T_RETRAITE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent_statut', src_ID, 'T_RETRAITE', src_T_RETRAITE, dest_T_RETRAITE, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE agent_statut SET ' || 'ID_ORIG = ' || src_ID_ORIG || ', ' || 'C_SOURCE = ' || src_C_SOURCE || ', ' || 'INDIVIDU_ID = ' || src_INDIVIDU_ID || ', ' || 'STRUCTURE_ID = ' || src_STRUCTURE_ID || ', ' || 'D_DEBUT = ' || src_D_DEBUT || ', ' || 'D_FIN = ' || src_D_FIN || ', ' || 'T_TITULAIRE = ' || src_T_TITULAIRE || ', ' || 'T_CDI = ' || src_T_CDI || ', ' || 'T_CDD = ' || src_T_CDD || ', ' || 'T_VACATAIRE = ' || src_T_VACATAIRE || ', ' || 'T_ENSEIGNANT = ' || src_T_ENSEIGNANT || ', ' || 'T_ADMINISTRATIF = ' || src_T_ADMINISTRATIF || ', ' || 'T_CHERCHEUR = ' || src_T_CHERCHEUR || ', ' || 'T_ETUDIANT = ' || src_T_ETUDIANT || ', ' || 'T_AUDITEUR_LIBRE = ' || src_T_AUDITEUR_LIBRE || ', ' || 'T_DOCTORANT = ' || src_T_DOCTORANT || ', ' || 'T_DETACHE_IN = ' || src_T_DETACHE_IN || ', ' || 'T_DETACHE_OUT = ' || src_T_DETACHE_OUT || ', ' || 'T_DISPO = ' || src_T_DISPO || ', ' || 'T_HEBERGE = ' || src_T_HEBERGE || ', ' || 'T_EMERITE = ' || src_T_EMERITE || ', ' || 'T_RETRAITE = ' || src_T_RETRAITE || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'agent_statut', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE agent_statut SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'agent_statut', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$