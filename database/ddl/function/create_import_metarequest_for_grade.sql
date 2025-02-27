CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_grade(src_id text, src_lib_court text, src_lib_long text, src_code text, src_histo text, dest_id text, dest_lib_court text, dest_lib_long text, dest_code text, dest_histo text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_LIB_COURT = coalesce(quote_literal(src_LIB_COURT), 'NULL');
    src_LIB_LONG = coalesce(quote_literal(src_LIB_LONG), 'NULL');
    src_CODE = coalesce(quote_literal(src_CODE), 'NULL');
    src_HISTO = coalesce(quote_literal(src_HISTO), 'NULL');
    dest_LIB_COURT = coalesce(quote_literal(dest_LIB_COURT), 'NULL');
    dest_LIB_LONG = coalesce(quote_literal(dest_LIB_LONG), 'NULL');
    dest_CODE = coalesce(quote_literal(dest_CODE), 'NULL');
    dest_HISTO = coalesce(quote_literal(dest_HISTO), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO grade(ID, LIB_COURT, LIB_LONG, CODE, HISTO, created_on) VALUES (' || quote_literal(src_ID) || ', ' || src_LIB_COURT || ', ' || src_LIB_LONG || ', ' || src_CODE || ', ' || src_HISTO || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'grade', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'LIB_COURT' doit être mis à jour
        IF (src_LIB_COURT <> dest_LIB_COURT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIB_COURT || '-' || import_hash;
            sql = 'UPDATE grade SET LIB_COURT = ' || src_LIB_COURT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'grade', src_ID, 'LIB_COURT', src_LIB_COURT, dest_LIB_COURT, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'LIB_LONG' doit être mis à jour
        IF (src_LIB_LONG <> dest_LIB_LONG) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIB_LONG || '-' || import_hash;
            sql = 'UPDATE grade SET LIB_LONG = ' || src_LIB_LONG || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'grade', src_ID, 'LIB_LONG', src_LIB_LONG, dest_LIB_LONG, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'CODE' doit être mis à jour
        IF (src_CODE <> dest_CODE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_CODE || '-' || import_hash;
            sql = 'UPDATE grade SET CODE = ' || src_CODE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'grade', src_ID, 'CODE', src_CODE, dest_CODE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'HISTO' doit être mis à jour
        IF (src_HISTO <> dest_HISTO) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_HISTO || '-' || import_hash;
            sql = 'UPDATE grade SET HISTO = ' || src_HISTO || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'grade', src_ID, 'HISTO', src_HISTO, dest_HISTO, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE grade SET ' || 'LIB_COURT = ' || src_LIB_COURT || ', ' || 'LIB_LONG = ' || src_LIB_LONG || ', ' || 'CODE = ' || src_CODE || ', ' || 'HISTO = ' || src_HISTO || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'grade', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE grade SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'grade', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$