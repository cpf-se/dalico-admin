
drop table if exists users restrict;

drop sequence if exists users_id_seq restrict;

create sequence users_id_seq;

create table users (
	id			integer				not null default nextval('users_id_seq'),
	username		varchar(50)			not null,
	password		varchar(255)			not null,
	email			varchar(100)			not null,
	lastname		varchar(32)			not null,
	firstname		varchar(32)			not null,
	activated		smallint			not null default 1,
	banned			smallint			not null default 0,
	ban_reason		varchar(255)			default null,
	new_password_key	varchar(50)			default null,
	new_password_requested	timestamp with time zone	default null,
	new_email		varchar(100)			default null,
	new_email_key		varchar(50)			default null,
	last_ip			inet				not null,
	last_login		timestamp with time zone	not null default CURRENT_TIMESTAMP,
	created			timestamp with time zone	not null default CURRENT_TIMESTAMP,
	modified		timestamp with time zone	not null default CURRENT_TIMESTAMP
);

alter sequence users_id_seq owned by users.id;

