
drop table if exists ci_sessions restrict;

create table ci_sessions (
	session_id	varchar(40)	not null default '0',
	ip_address	inet		not null default '127.0.0.2',
	user_agent	varchar(150)	not null,
	last_activity	integer		not null default 0,
	user_data	text,
	primary key (session_id),
	constraint last_activity_is_unsigned check(last_activity >= 0)
);

