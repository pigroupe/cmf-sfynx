--suppression de tous les droits a public pour les tables et les schemas--
select 'revoke all on table '||schemaname||'.'|| tablename ||' from public;' from pg_tables where tableowner = 'sfynx';
select distinct 'revoke all on schema '||schemaname||' '||' from public;' from pg_tables where tableowner = 'sfynx';

--visu des schema par le groupe jonas et de creation pour le schema quartz--
select distinct 'grant usage on schema '||schemaname||' '||' to util_g;' from pg_tables where tableowner = 'sfynx';

--donner tous les droits a util_g pour tt les tables--
select 'grant all on table '||schemaname||'.'|| tablename ||' to util_g;' from pg_tables where tableowner = 'sfynx';

--donner tous les droits a util_g pour tt les sequences--
select 'grant all on table '||sequence_schema||'.'|| sequence_name||' to util_g;' from INFORMATION_SCHEMA.SEQUENCES;

--visu des vue et droits pour le groupe utilisateur util_g,jonas_g,public --
select distinct 'GRANT ALL ON TABLE '||schemaname||'.'||viewname||' to util_g;' from pg_views where viewowner <> 'postgres';
select distinct 'GRANT ALL ON TABLE '||schemaname||'.'||viewname||' to jonas_g;' from pg_views where viewowner <> 'postgres';
select distinct 'GRANT ALL ON TABLE '||schemaname||'.'||viewname||' to public;' from pg_views where viewowner <> 'postgres';
