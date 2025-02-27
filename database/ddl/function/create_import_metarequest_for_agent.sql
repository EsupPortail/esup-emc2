CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_agent(src_c_individu text, src_c_src_individu text, src_c_source text, src_prenom text, src_nom_usage text, src_bap_id text, src_corps_id text, src_grade_id text, dest_c_individu text, dest_c_src_individu text, dest_c_source text, dest_prenom text, dest_nom_usage text, dest_bap_id text, dest_corps_id text, dest_grade_id text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_C_SRC_INDIVIDU = coalesce(quote_literal(src_C_SRC_INDIVIDU), 'NULL');
    src_C_SOURCE = coalesce(quote_literal(src_C_SOURCE), 'NULL');
    src_PRENOM = coalesce(quote_literal(src_PRENOM), 'NULL');
    src_NOM_USAGE = coalesce(quote_literal(src_NOM_USAGE), 'NULL');
    src_BAP_ID = coalesce(quote_literal(src_BAP_ID), 'NULL');
    src_CORPS_ID = coalesce(quote_literal(src_CORPS_ID), 'NULL');
    src_GRADE_ID = coalesce(quote_literal(src_GRADE_ID), 'NULL');
    dest_C_SRC_INDIVIDU = coalesce(quote_literal(dest_C_SRC_INDIVIDU), 'NULL');
    dest_C_SOURCE = coalesce(quote_literal(dest_C_SOURCE), 'NULL');
    dest_PRENOM = coalesce(quote_literal(dest_PRENOM), 'NULL');
    dest_NOM_USAGE = coalesce(quote_literal(dest_NOM_USAGE), 'NULL');
    dest_BAP_ID = coalesce(quote_literal(dest_BAP_ID), 'NULL');
    dest_CORPS_ID = coalesce(quote_literal(dest_CORPS_ID), 'NULL');
    dest_GRADE_ID = coalesce(quote_literal(dest_GRADE_ID), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_C_INDIVIDU IS NOT NULL AND dest_C_INDIVIDU IS NULL) THEN
        operation = 'insert';
        hash = src_C_INDIVIDU || '-' || import_hash;
        sql = 'INSERT INTO agent(C_INDIVIDU, C_SRC_INDIVIDU, C_SOURCE, PRENOM, NOM_USAGE, BAP_ID, CORPS_ID, GRADE_ID, created_on) VALUES (' || quote_literal(src_C_INDIVIDU) || ', ' || src_C_SRC_INDIVIDU || ', ' || src_C_SOURCE || ', ' || src_PRENOM || ', ' || src_NOM_USAGE || ', ' || src_BAP_ID || ', ' || src_CORPS_ID || ', ' || src_GRADE_ID || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'agent', src_C_INDIVIDU, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_C_INDIVIDU IS NOT NULL AND dest_C_INDIVIDU IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'C_SRC_INDIVIDU' doit être mis à jour
        IF (src_C_SRC_INDIVIDU <> dest_C_SRC_INDIVIDU) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_C_SRC_INDIVIDU || '-' || import_hash;
            sql = 'UPDATE agent SET C_SRC_INDIVIDU = ' || src_C_SRC_INDIVIDU || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'C_SRC_INDIVIDU', src_C_SRC_INDIVIDU, dest_C_SRC_INDIVIDU, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'C_SOURCE' doit être mis à jour
        IF (src_C_SOURCE <> dest_C_SOURCE) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_C_SOURCE || '-' || import_hash;
            sql = 'UPDATE agent SET C_SOURCE = ' || src_C_SOURCE || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'C_SOURCE', src_C_SOURCE, dest_C_SOURCE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'PRENOM' doit être mis à jour
        IF (src_PRENOM <> dest_PRENOM) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_PRENOM || '-' || import_hash;
            sql = 'UPDATE agent SET PRENOM = ' || src_PRENOM || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'PRENOM', src_PRENOM, dest_PRENOM, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'NOM_USAGE' doit être mis à jour
        IF (src_NOM_USAGE <> dest_NOM_USAGE) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_NOM_USAGE || '-' || import_hash;
            sql = 'UPDATE agent SET NOM_USAGE = ' || src_NOM_USAGE || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'NOM_USAGE', src_NOM_USAGE, dest_NOM_USAGE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'BAP_ID' doit être mis à jour
        IF (src_BAP_ID <> dest_BAP_ID) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_BAP_ID || '-' || import_hash;
            sql = 'UPDATE agent SET BAP_ID = ' || src_BAP_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'BAP_ID', src_BAP_ID, dest_BAP_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'CORPS_ID' doit être mis à jour
        IF (src_CORPS_ID <> dest_CORPS_ID) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_CORPS_ID || '-' || import_hash;
            sql = 'UPDATE agent SET CORPS_ID = ' || src_CORPS_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'CORPS_ID', src_CORPS_ID, dest_CORPS_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'GRADE_ID' doit être mis à jour
        IF (src_GRADE_ID <> dest_GRADE_ID) THEN
            operation = 'update';
            hash = dest_C_INDIVIDU || '-' || dest_GRADE_ID || '-' || import_hash;
            sql = 'UPDATE agent SET GRADE_ID = ' || src_GRADE_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'agent', src_C_INDIVIDU, 'GRADE_ID', src_GRADE_ID, dest_GRADE_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_C_INDIVIDU IS NOT NULL AND dest_C_INDIVIDU IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_C_INDIVIDU || '-' || import_hash;
        sql = 'UPDATE agent SET ' || 'C_SRC_INDIVIDU = ' || src_C_SRC_INDIVIDU || ', ' || 'C_SOURCE = ' || src_C_SOURCE || ', ' || 'PRENOM = ' || src_PRENOM || ', ' || 'NOM_USAGE = ' || src_NOM_USAGE || ', ' || 'BAP_ID = ' || src_BAP_ID || ', ' || 'CORPS_ID = ' || src_CORPS_ID || ', ' || 'GRADE_ID = ' || src_GRADE_ID || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'agent', src_C_INDIVIDU, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_C_INDIVIDU IS NULL AND dest_C_INDIVIDU IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_C_INDIVIDU || '-' || import_hash;
        sql = 'UPDATE agent SET deleted_on = LOCALTIMESTAMP(0) WHERE C_INDIVIDU = ' || quote_literal(dest_C_INDIVIDU) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'agent', src_C_INDIVIDU, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$