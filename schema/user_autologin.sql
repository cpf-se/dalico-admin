
drop table if exists user_autologin restrict;

create table user_autologin (
	key_id		char(32)			not null,
	user_id		integer				not null default 0,
	user_agent	varchar(150)			not null,
	last_ip		varchar(40)			not null,
	last_login	timestamp with time zone	not null default CURRENT_TIMESTAMP,
	primary key (key_id, user_id)
);

