
drop table if exists user_profiles restrict;

drop sequence if exists user_profiles_id_seq restrict;

create sequence user_profiles_id_seq;

create table user_profiles (
	id	integer		not null default nextval('user_profiles_id_seq'),
	user_id	integer		not null,
	country	varchar(20)	default null,
	website	varchar(255)	default null,
	primary key (id)
);

alter sequence user_profiles_id_seq owned by user_profiles.id;

