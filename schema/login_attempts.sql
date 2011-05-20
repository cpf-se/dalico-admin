
drop table if exists login_attempts restrict;
drop sequence if exists login_attempts_id_seq restrict;

create sequence login_attempts_id_seq;

create table login_attempts (
	id		integer				not null default nextval('login_attempts_id_seq'),
	ip_address	inet				not null,
	login		varchar(50)			not null,
	time		timestamp with time zone	not null default CURRENT_TIMESTAMP,
	primary key (id)
);

alter sequence login_attempts_id_seq owned by login_attempts.id;

