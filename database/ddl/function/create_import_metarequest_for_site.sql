CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_site(src_id text, src_nom text, src_libelle text, dest_id text, dest_nom text, dest_libelle text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_NOM = coalesce(quote_literal(src_NOM), 'NULL');
    src_LIBELLE = coalesce(quote_literal(src_LIBELLE), 'NULL');
    dest_NOM = coalesce(quote_literal(dest_NOM), 'NULL');
    dest_LIBELLE = coalesce(quote_literal(dest_LIBELLE), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO site(ID, NOM, LIBELLE, created_on) VALUES (' || quote_literal(src_ID) || ', ' || src_NOM || ', ' || src_LIBELLE || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'site', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'NOM' doit être mis à jour
        IF (src_NOM <> dest_NOM) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_NOM || '-' || import_hash;
            sql = 'UPDATE site SET NOM = ' || src_NOM || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'site', src_ID, 'NOM', src_NOM, dest_NOM, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'LIBELLE' doit être mis à jour
        IF (src_LIBELLE <> dest_LIBELLE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIBELLE || '-' || import_hash;
            sql = 'UPDATE site SET LIBELLE = ' || src_LIBELLE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'site', src_ID, 'LIBELLE', src_LIBELLE, dest_LIBELLE, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE site SET ' || 'NOM = ' || src_NOM || ', ' || 'LIBELLE = ' || src_LIBELLE || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'site', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE site SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'site', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$