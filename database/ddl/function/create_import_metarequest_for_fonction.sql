CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_fonction(src_id text, src_parent_id text, src_code text, src_niveau text, dest_id text, dest_parent_id text, dest_code text, dest_niveau text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_PARENT_ID = coalesce(quote_literal(src_PARENT_ID), 'NULL');
    src_CODE = coalesce(quote_literal(src_CODE), 'NULL');
    src_NIVEAU = coalesce(quote_literal(src_NIVEAU), 'NULL');
    dest_PARENT_ID = coalesce(quote_literal(dest_PARENT_ID), 'NULL');
    dest_CODE = coalesce(quote_literal(dest_CODE), 'NULL');
    dest_NIVEAU = coalesce(quote_literal(dest_NIVEAU), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO fonction(ID, PARENT_ID, CODE, NIVEAU, created_on) VALUES (' || quote_literal(src_ID) || ', ' || src_PARENT_ID || ', ' || src_CODE || ', ' || src_NIVEAU || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'fonction', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'PARENT_ID' doit être mis à jour
        IF (src_PARENT_ID <> dest_PARENT_ID) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_PARENT_ID || '-' || import_hash;
            sql = 'UPDATE fonction SET PARENT_ID = ' || src_PARENT_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction', src_ID, 'PARENT_ID', src_PARENT_ID, dest_PARENT_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'CODE' doit être mis à jour
        IF (src_CODE <> dest_CODE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_CODE || '-' || import_hash;
            sql = 'UPDATE fonction SET CODE = ' || src_CODE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction', src_ID, 'CODE', src_CODE, dest_CODE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'NIVEAU' doit être mis à jour
        IF (src_NIVEAU <> dest_NIVEAU) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_NIVEAU || '-' || import_hash;
            sql = 'UPDATE fonction SET NIVEAU = ' || src_NIVEAU || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction', src_ID, 'NIVEAU', src_NIVEAU, dest_NIVEAU, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE fonction SET ' || 'PARENT_ID = ' || src_PARENT_ID || ', ' || 'CODE = ' || src_CODE || ', ' || 'NIVEAU = ' || src_NIVEAU || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'fonction', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE fonction SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'fonction', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$