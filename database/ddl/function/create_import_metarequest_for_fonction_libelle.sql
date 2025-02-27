CREATE OR REPLACE FUNCTION public.create_import_metarequest_for_fonction_libelle(src_id text, src_fonction_id text, src_libelle text, src_genre text, src_defaut text, dest_id text, dest_fonction_id text, dest_libelle text, dest_genre text, dest_defaut text, dest_deleted_on timestamp with time zone, import_hash character varying)
 RETURNS character varying
 LANGUAGE plpgsql
AS $function$
DECLARE
    operation VARCHAR(64);
    hash VARCHAR(255);
    sql TEXT;
BEGIN
    -- normalisation des valeurs d'entrée
    src_FONCTION_ID = coalesce(quote_literal(src_FONCTION_ID), 'NULL');
    src_LIBELLE = coalesce(quote_literal(src_LIBELLE), 'NULL');
    src_GENRE = coalesce(quote_literal(src_GENRE), 'NULL');
    src_DEFAUT = coalesce(quote_literal(src_DEFAUT), 'NULL');
    dest_FONCTION_ID = coalesce(quote_literal(dest_FONCTION_ID), 'NULL');
    dest_LIBELLE = coalesce(quote_literal(dest_LIBELLE), 'NULL');
    dest_GENRE = coalesce(quote_literal(dest_GENRE), 'NULL');
    dest_DEFAUT = coalesce(quote_literal(dest_DEFAUT), 'NULL');

    -- l'enregistrement existe dans la source mais pas dans la destination : il devra être ajouté
    IF (src_ID IS NOT NULL AND dest_ID IS NULL) THEN
        operation = 'insert';
        hash = src_ID || '-' || import_hash;
        sql = 'INSERT INTO fonction_libelle(ID, FONCTION_ID, LIBELLE, GENRE, DEFAUT, created_on) VALUES (' || quote_literal(src_ID) || ', ' || src_FONCTION_ID || ', ' || src_LIBELLE || ', ' || src_GENRE || ', ' || src_DEFAUT || ', ' || 'LOCALTIMESTAMP(0)) ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('insert', 'fonction_libelle', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination et n'est pas historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        -- 'FONCTION_ID' doit être mis à jour
        IF (src_FONCTION_ID <> dest_FONCTION_ID) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_FONCTION_ID || '-' || import_hash;
            sql = 'UPDATE fonction_libelle SET FONCTION_ID = ' || src_FONCTION_ID || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction_libelle', src_ID, 'FONCTION_ID', src_FONCTION_ID, dest_FONCTION_ID, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'LIBELLE' doit être mis à jour
        IF (src_LIBELLE <> dest_LIBELLE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_LIBELLE || '-' || import_hash;
            sql = 'UPDATE fonction_libelle SET LIBELLE = ' || src_LIBELLE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction_libelle', src_ID, 'LIBELLE', src_LIBELLE, dest_LIBELLE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'GENRE' doit être mis à jour
        IF (src_GENRE <> dest_GENRE) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_GENRE || '-' || import_hash;
            sql = 'UPDATE fonction_libelle SET GENRE = ' || src_GENRE || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction_libelle', src_ID, 'GENRE', src_GENRE, dest_GENRE, sql, LOCALTIMESTAMP(0), hash);
        END IF;
        -- 'DEFAUT' doit être mis à jour
        IF (src_DEFAUT <> dest_DEFAUT) THEN
            operation = 'update';
            hash = dest_ID || '-' || dest_DEFAUT || '-' || import_hash;
            sql = 'UPDATE fonction_libelle SET DEFAUT = ' || src_DEFAUT || ', updated_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
            sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
            INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('update', 'fonction_libelle', src_ID, 'DEFAUT', src_DEFAUT, dest_DEFAUT, sql, LOCALTIMESTAMP(0), hash);
        END IF;

    END IF;

    -- l'enregistrement existe dans la destination mais historisé : il sera dé-historisé
    IF (src_ID IS NOT NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NOT NULL) THEN
        operation = 'undelete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE fonction_libelle SET ' || 'FONCTION_ID = ' || src_FONCTION_ID || ', ' || 'LIBELLE = ' || src_LIBELLE || ', ' || 'GENRE = ' || src_GENRE || ', ' || 'DEFAUT = ' || src_DEFAUT || ', ' || 'updated_on = LOCALTIMESTAMP(0), deleted_on = null WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('undelete', 'fonction_libelle', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    -- l'enregistrement existe dans la destination mais plus dans la source : il sera historisé
    IF (src_ID IS NULL AND dest_ID IS NOT NULL and dest_deleted_on IS NULL) THEN
        operation = 'delete';
        hash = dest_ID || '-' || import_hash;
        sql = 'UPDATE fonction_libelle SET deleted_on = LOCALTIMESTAMP(0) WHERE ID = ' || quote_literal(dest_ID) || ' ;' ;
        sql = sql || ' UPDATE import_reg SET executed_on = LOCALTIMESTAMP(0) WHERE import_hash = ' || quote_literal(hash) || ' ;' ;
        INSERT INTO import_reg(operation, table_name, source_code, field_name, to_value, from_value, sql, created_on, import_hash) VALUES ('delete', 'fonction_libelle', src_ID, null, null, null, sql, LOCALTIMESTAMP(0), hash);
    END IF;

    RETURN operation;
END; $function$