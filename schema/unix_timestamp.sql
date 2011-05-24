
create or replace function unix_timestamp() returns integer as $$
select round(extract(epoch from abstime(now())))::int4 as result;
$$ language 'sql';

create or replace function unix_timestamp(timestamp with time zone) returns integer as $$
select round(extract(epoch from abstime($1)))::int4 as result;
$$ language 'sql';

