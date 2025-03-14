CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_structure(src_id text, src_code text, src_libelle_court text, src_libelle_long text, src_type text, dest_id text, dest_code text, dest_libelle_court text, dest_libelle_long text, dest_type text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_CODE = coalesce(src_CODE, 'null');
    src_LIBELLE_COURT = coalesce(src_LIBELLE_COURT, 'null');
    src_LIBELLE_LONG = coalesce(src_LIBELLE_LONG, 'null');
    src_TYPE = coalesce(src_TYPE, 'null');
    dest_CODE = coalesce(dest_CODE, 'null');
    dest_LIBELLE_COURT = coalesce(dest_LIBELLE_COURT, 'null');
    dest_LIBELLE_LONG = coalesce(dest_LIBELLE_LONG, 'null');
    dest_TYPE = coalesce(dest_TYPE, 'null');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO structure(ID, CODE, LIBELLE_COURT, LIBELLE_LONG, TYPE, created_on) VALUES (' || quote_literal(src_ID) || ', ' || quote_literal(src_CODE) || ', ' || quote_literal(src_LIBELLE_COURT) || ', ' || quote_literal(src_LIBELLE_LONG) || ', ' || quote_literal(src_TYPE) || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'structure', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'CODE' doit être mis à jour
        IF (src_CODE <> dest_CODE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_CODE || '-' || import_hash;
            sql = 'UPDATE structure SET CODE = ' || quote_literal(src_CODE) || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'structure', src_ID, 'CODE', src_CODE, dest_CODE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'LIBELLE_COURT' doit être mis à jour
        IF (src_LIBELLE_COURT <> dest_LIBELLE_COURT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIBELLE_COURT || '-' || import_hash;
            sql = 'UPDATE structure SET LIBELLE_COURT = ' || quote_literal(src_LIBELLE_COURT) || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'structure', src_ID, 'LIBELLE_COURT', src_LIBELLE_COURT, dest_LIBELLE_COURT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'LIBELLE_LONG' doit être mis à jour
        IF (src_LIBELLE_LONG <> dest_LIBELLE_LONG) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIBELLE_LONG || '-' || import_hash;
            sql = 'UPDATE structure SET LIBELLE_LONG = ' || quote_literal(src_LIBELLE_LONG) || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'structure', src_ID, 'LIBELLE_LONG', src_LIBELLE_LONG, dest_LIBELLE_LONG, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'TYPE' doit être mis à jour
        IF (src_TYPE <> dest_TYPE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_TYPE || '-' || import_hash;
            sql = 'UPDATE structure SET TYPE = ' || quote_literal(src_TYPE) || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'structure', src_ID, 'TYPE', src_TYPE, dest_TYPE, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE structure SET ' || 'CODE = ' || quote_literal(src_CODE) || ', ' || 'LIBELLE_COURT = ' || quote_literal(src_LIBELLE_COURT) || ', ' || 'LIBELLE_LONG = ' || quote_literal(src_LIBELLE_LONG) || ', ' || 'TYPE = ' || quote_literal(src_TYPE) || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'structure', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE structure SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'structure', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$